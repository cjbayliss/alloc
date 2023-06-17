<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Term.php 23775 2011-03-01 17:25:24Z ralph $
 */

/** Zend_Search_Lucene_Search_Query */
require_once 'Zend/Search/Lucene/Search/Query.php';

/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Search_Lucene_Search_Query_Term extends Zend_Search_Lucene_Search_Query
{
    /**
     * Term to find.
     */
    private \Zend_Search_Lucene_Index_Term $_term;

    /**
     * Documents vector.
     */
    private ?array $_docVector = null;

    /**
     * Term freqs vector.
     * array(docId => freq, ...)
     */
    private ?int $_termFreqs = null;

    /**
     * Zend_Search_Lucene_Search_Query_Term constructor
     *
     * @param Zend_Search_Lucene_Index_Term $zendSearchLuceneIndexTerm
     * @param boolean $sign
     */
    public function __construct(Zend_Search_Lucene_Index_Term $zendSearchLuceneIndexTerm)
    {
        $this->_term = $zendSearchLuceneIndexTerm;
    }

    /**
     * Re-write query into primitive queries in the context of specified index
     *
     * @param Zend_Search_Lucene_Interface $zendSearchLucene
     * @return Zend_Search_Lucene_Search_Query
     */
    public function rewrite(Zend_Search_Lucene_Interface $zendSearchLucene)
    {
        if ($this->_term->field != null) {
            return $this;
        } else {
            require_once 'Zend/Search/Lucene/Search/Query/MultiTerm.php';
            $zendSearchLuceneSearchQueryMultiTerm = new Zend_Search_Lucene_Search_Query_MultiTerm();
            $zendSearchLuceneSearchQueryMultiTerm->setBoost($this->getBoost());

            require_once 'Zend/Search/Lucene/Index/Term.php';
            foreach ($zendSearchLucene->getFieldNames(true) as $fieldName) {
                $term = new Zend_Search_Lucene_Index_Term($this->_term->text, $fieldName);

                $zendSearchLuceneSearchQueryMultiTerm->addTerm($term);
            }

            return $zendSearchLuceneSearchQueryMultiTerm->rewrite($zendSearchLucene);
        }
    }

    /**
     * Optimize query in the context of specified index
     *
     * @param Zend_Search_Lucene_Interface $zendSearchLucene
     * @return Zend_Search_Lucene_Search_Query
     */
    public function optimize(Zend_Search_Lucene_Interface $zendSearchLucene)
    {
        // Check, that index contains specified term
        if (!$zendSearchLucene->hasTerm($this->_term)) {
            require_once 'Zend/Search/Lucene/Search/Query/Empty.php';
            return new Zend_Search_Lucene_Search_Query_Empty();
        }

        return $this;
    }

    /**
     * Constructs an appropriate Weight implementation for this query.
     *
     * @param Zend_Search_Lucene_Interface $zendSearchLucene
     * @return Zend_Search_Lucene_Search_Weight
     */
    public function createWeight(Zend_Search_Lucene_Interface $zendSearchLucene)
    {
        require_once 'Zend/Search/Lucene/Search/Weight/Term.php';
        $this->_weight = new Zend_Search_Lucene_Search_Weight_Term($this->_term, $this, $zendSearchLucene);
        return $this->_weight;
    }

    /**
     * Execute query in context of index reader
     * It also initializes necessary internal structures
     *
     * @param Zend_Search_Lucene_Interface $zendSearchLucene
     * @param Zend_Search_Lucene_Index_DocsFilter|null $docsFilter
     */
    public function execute(Zend_Search_Lucene_Interface $zendSearchLucene, $docsFilter = null)
    {
        $this->_docVector = array_flip($zendSearchLucene->termDocs($this->_term, $docsFilter));
        $this->_termFreqs = $zendSearchLucene->termFreqs($this->_term, $docsFilter);

        // Initialize weight if it's not done yet
        $this->_initWeight($zendSearchLucene);
    }

    /**
     * Get document ids likely matching the query
     *
     * It's an array with document ids as keys (performance considerations)
     *
     * @return array
     */
    public function matchedDocs()
    {
        return $this->_docVector;
    }

    /**
     * Score specified document
     *
     * @param integer $docId
     * @param Zend_Search_Lucene_Interface $zendSearchLucene
     * @return float
     */
    public function score($docId, Zend_Search_Lucene_Interface $zendSearchLucene)
    {
        if (isset($this->_docVector[$docId])) {
            return $zendSearchLucene->getSimilarity()->tf($this->_termFreqs[$docId]) *
                $this->_weight->getValue() *
                $zendSearchLucene->norm($docId, $this->_term->field) *
                $this->getBoost();
        } else {
            return 0;
        }
    }

    /**
     * Return query terms
     *
     * @return array
     */
    public function getQueryTerms()
    {
        return [$this->_term];
    }

    /**
     * Return query term
     *
     * @return Zend_Search_Lucene_Index_Term
     */
    public function getTerm()
    {
        return $this->_term;
    }

    /**
     * Query specific matches highlighting
     *
     * @param Zend_Search_Lucene_Search_Highlighter_Interface $zendSearchLuceneSearchHighlighter Highlighter object (also contains doc for highlighting)
     */
    protected function _highlightMatches(Zend_Search_Lucene_Search_Highlighter_Interface $zendSearchLuceneSearchHighlighter)
    {
        $zendSearchLuceneSearchHighlighter->highlight($this->_term->text);
    }

    /**
     * Print a query
     *
     * @return string
     */
    public function __toString()
    {
        // It's used only for query visualisation, so we don't care about characters escaping
        if ($this->_term->field !== null) {
            $query = $this->_term->field . ':';
        } else {
            $query = '';
        }

        $query .= $this->_term->text;

        if ($this->getBoost() != 1) {
            $query = $query . '^' . round($this->getBoost(), 4);
        }

        return $query;
    }
}
