/* Standard AJAX Funktion */
var http_request = false;

if (window.XMLHttpRequest)
{
  http_request = new XMLHttpRequest();
  if (http_request.overrideMimeType)
  {
    http_request.overrideMimeType('text/xml');
  }
} else if (window.ActiveXObject)
{
  try
  {
    http_request = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e)
  {
    try
    {
      http_request = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (e) {
    }
  }
}

window.onDomReady = initReady;

function initReady(fn)
{
  if (document.addEventListener)
  {
    document.addEventListener("DOMContentLoaded", fn, false);
  } else
  {
    document.onreadystatechange = function () {
      readyState(fn)
    }
  }
}

function readyState(func)
{
  if (document.readyState == "interactive" || document.readyState == "complete")
  {
    func();
  }
}

// Ergebnis AJAX verarbeiten und zurückgeben
function insert_result(inner_object, my_script)
{
  if (http_request)
  {
    http_request.open('GET', my_script);

    http_request.onreadystatechange = function ()
    {
      if (http_request.readyState == 4)
      {
        document.getElementById(inner_object).innerHTML = http_request.responseText;
      }
    };
    http_request.send(null);
  }
}

// Ergebnis AJAX verarbeiten und zurückgeben
function insert_result_loader(inner_object, my_script, ServerName)
{
  if (http_request)
  {
    document.getElementById(inner_object).innerHTML = '<img src="' + ServerName + '/assets/images/ajax-loader-1.gif">';
    http_request.open('GET', my_script);

    http_request.onreadystatechange = function ()
    {
      if (http_request.readyState == 4)
      {
        document.getElementById(inner_object).innerHTML = http_request.responseText;
      }
    };
    http_request.send(null);
  }
}

/* Alle ankommenden Daten speicher */
var request;


function show(element)
{
  document.getElementById(element).style.display = 'inline';
}

function hide(element)
{
  document.getElementById(element).style.display = 'none';
}


function update_ticker(ServerName)
{
  document.getElementById('reload').style.display = 'none';
  document.getElementById('loader').style.display = 'inline';

  insert_result('live_ticker', ServerName + "/controller/ajax_load_ticker.php");

  document.getElementById('loader').style.display = 'none';
  document.getElementById('reload').style.display = 'inline';
}

function checkbox(element)
{
  document.getElementById(element).checked = 'checked';
}

function lookup(inputString, ServerName)
{
  if (inputString.length <= 1)
  {
    $('#suggestions').hide();
  } else
  {
    $.post(ServerName + "/controller/ajax_email_search.php", {queryString: "" + inputString + ""}, function (data)
    {
      if (data.length > 0)
      {
        $('#suggestions').show();
        $('#autoSuggestionsList').html(data);
      }
    });
  }
}

function fill(thisValue)
{
  $('#inputString').val(thisValue);
  $('#suggestions').hide();
}


function update_Logfile(ServerName)
{
  document.getElementById('reload').style.display = 'none';
  document.getElementById('loader').style.display = 'inline';

  insert_result('live_Logfile', ServerName + "/controller/kgu-ftp/ajax_load_Logfile.php");

  document.getElementById('loader').style.display = 'none';
  document.getElementById('reload').style.display = 'inline';
} 

function startPortScan(target) {
  var max  = Math.round(document.getElementById('range_ende').value);
  var port = Math.round(document.getElementById('range_start').value);
  
  post_ajax_port(port,max,target);
}

function post_ajax_port(port,max,target) {  
  if(port<=max){
      document.getElementById('check_current_port').innerHTML = '<h3>Check Port: ' + port + '</h3><hr />';
      document.getElementById('check_start_button_port').style.display = 'none';
      // document.title = Math.round(calc_process(max, port)) + '% accomplished' ;

      $.ajax({
          type: "POST",
          url: "/controller/inventory/ajax/port.php",
          data: "port=" + port + "&ip=" + target,
          success: function(phpData){
              document.getElementById('check_content').innerHTML = document.getElementById('check_content').innerHTML + phpData;
              post_ajax_port(port + 1, max, target);
          }
      })
  }else{
      document.getElementById('check_start_button_port').style.display = 'block';
  }
}

function post_ajax_dns(port, max, target) {
  if(port!=max){
      document.getElementById('check_current_port').innerHTML = '(Check DNS: ' + target + '.' + port + ')';
      document.getElementById('check_menue_main').style.display = 'none';
      // document.title = Math.round(calc_process(max, port)) + '% accomplished' ;

      $.ajax({
          type: "POST",
          url: "/controller/inventory/ajax/ip.php",
          data: "port=" + port + "&ip=" + target,
          success: function(phpData){
              document.getElementById('check_content').innerHTML = document.getElementById('check_content').innerHTML + phpData;
              post_ajax_dns(port + 1, max, target);
          }
      })
  } else{
    document.getElementById('check_start_button_dns').style.display = 'block';
  }
}

function calc_process(g, w){
  var p = w/g * 100;
  return p;
}

