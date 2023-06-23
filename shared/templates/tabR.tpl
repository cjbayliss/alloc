<a href="{$url}" class="tab{$active} noselect" style="left:{$x}px;" unselectable="on">{$name}</a>

{if $active && $name == "Home" || (!empty($current_user->prefs["customizedTheme2"]) && $current_user->prefs["customizedTheme2"] != 4)}
  <style>
  div#main {
            border-radius: 0 0.2rem 0.2rem 0.2rem !important;
  }
  </style>
{/}
