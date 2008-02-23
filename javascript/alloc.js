var http_request = new Array();

function makeAjaxRequest(url,actionFunction,number,entityid) {
    http_request[number] = false;

    if (window.XMLHttpRequest) { // Mozilla, Safari,...
        http_request[number] = new XMLHttpRequest();
        if (http_request[number].overrideMimeType) { http_request[number].overrideMimeType('text/xml'); }

    } else if (window.ActiveXObject) { // IE
        try { 
          http_request[number] = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try { 
              http_request[number] = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
            }
        }
    }

    if (!http_request[number]) {
        // alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }
    // Here's how to be a bit crafty object.onreadystatechange=Function("yourfunction("+yourargumentlist+");");
    http_request[number].onreadystatechange = Function(actionFunction+"("+number+",'"+entityid+"');");
    http_request[number].open('GET', url, true);
    http_request[number].send(null);
}

function callbackReceiver(number,entityid) {
  if (http_request[number].readyState == 4) {
    if (http_request[number].status == 200) {
      document.getElementById(entityid).innerHTML = http_request[number].responseText;
    }
  }
}

function set_grow_shrink_box(id, display, images, text, id_to_hide) {
  if (display == 'none') {
    display = 'inline';
    display_to_hide = 'none';
    image = images+'small_shrink.gif'
  } else {
    display = 'none';
    display_to_hide = 'inline';
    image = images+'small_grow.gif'
  }

  document.getElementById(id).style.display=display;

  if (id_to_hide) {
    document.getElementById(id_to_hide).style.display=display_to_hide;
  }

  str = "<nobr><a class=\"growshrink nobr\" href=\"#\" onClick=\"return set_grow_shrink_box('"+id+"','"+display+"','"+images+"','"+text+"','"+id_to_hide+"');\">"+text+"<img border=\"0\" src=\""+image+"\"></a></nobr>"
  document.getElementById('button_'+id).innerHTML = str;
  return false;
}

function sidebyside_activate(id,arr) {
  
  if (id == "sbsAll") {
    for (var i=0; i<arr.length; i++) {
      if (arr[i] != "sbsAll") {
        document.getElementById(arr[i]).style.display='inline';
        document.getElementById('sbs_link_' + arr[i]).className = "sidebyside"
      }
    }
    document.getElementById('sbs_link_' + id).className = "sidebyside_active";

  } else {

    for (var i=0; i<arr.length; i++) {
      if (arr[i] != "sbsAll") {
        document.getElementById(arr[i]).style.display='none';
      }
      document.getElementById('sbs_link_' + arr[i]).className = "sidebyside"
    }
    document.getElementById('sbs_link_' + id).className = "sidebyside_active";
    document.getElementById(id).style.display='inline';
  }
}


function getElementsByClass(searchClass,node,tag) {
  var classEls = new Array();
  if (node == null) {
    node = document;
  }
  if (tag == null) {
    tag = '*';
  }
  var els = node.getElementsByTagName(tag);
  var elsLen = els.length;
  var pattern = new RegExp('(^|\\\\s)'+searchClass+'(\\\\s|$)');
  for (i = 0, j = 0; i < elsLen; i++) {
    if (pattern.test(els[i].className)) {
      classEls[j] = els[i];
      j++;
    }
  }
  return classEls;
}

// function adjust_textarea(textarea, default_num_rows) {
//   n = textarea.value.split('\n').length;
//   if (n < default_num_rows) {
//     n = default_num_rows
//   }
//   if (textarea.rows != n) {
//     textarea.rows = n;
//   }
// } 

// this function dynamically resizes a text area as data is inputted
function adjust_textarea(textarea, default_height) {
  // a div is setup off screen, we use that div to determine the height of the textarea
  shadow = document.getElementById("shadow_" + textarea.id);
  shadow.style.width=parseInt(textarea.clientWidth-8)+'px';
  shadow.innerHTML = textarea.value.replace(/[\n]/g,'<br />&nbsp;');
  shadow_height = shadow.clientHeight;
  if(shadow_height < default_height) {
    n = default_height;
  } else {
    n = shadow_height+14;
  }
  textarea.style.height = n+'px';
} 


