#!/bin/bash

revno=$(bzr revno)
bzr push
cd /cyber/devel/bzr/alloc/ && bzr export --format tgz ~/allocPSA.${revno}.tgz 
cd ~
mv allocPSA.${revno}.tgz html/



