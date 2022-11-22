// Funkce pro vytvoreni noveho pole pro nahrani souboru
function newFile(i)
{
  var x = i;
  var y = i +1;

  var label = document.createElement("label");
  label.innerText = "Přidat přílohu";

  var labelClass = document.createAttribute("class");
  labelClass.value = "control-label col-sm-3";

  label.setAttributeNode(labelClass);

  var input = document.createElement("input");

  var type = document.createAttribute("type");
  type.value = "file";

  var name = document.createAttribute("name");
  name.value = "fileInput"+y;

  var inputClass = document.createAttribute("class");
  inputClass.value = "control-label col-sm-5";

  var onChange = document.createAttribute("onChange");
  onChange.value = "newFile("+y+")";

  input.setAttributeNode(type);
  input.setAttributeNode(name);
  input.setAttributeNode(inputClass);
  input.setAttributeNode(onChange);

  document.getElementById("file"+x).appendChild(label);
  document.getElementById("file"+x).appendChild(input);

  var div  = document.createElement("div");

  var id = document.createAttribute("id");
  id.value = "file"+y;

  var divClass = document.createAttribute("class");
  divClass.value = "form-group pb-2";

  div.setAttributeNode(divClass);
  div.setAttributeNode(id);
  document.getElementById("files").appendChild(div);
}

// Fuknce pro kontrolu zda je zvoleny alespon jeden checkbox
function validation(x)
{
  var y = 1;
  var check = false;
  for (let i = 0; i < x; i++) 
  {
    var checkbox = document.getElementById('check'+y);
    if(checkbox.checked)
    {
      check = true;
    }
    y++
  }
  if(!check)
  {
    window.alert('Zaškrtněte alespoň jedno pole z "Další typ akce"');
  }
  return check;
}