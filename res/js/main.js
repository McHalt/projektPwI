function discardMessage(m){
    m.target.remove();
}

function addMessagesListeners(m){
    m.addEventListener('click', discardMessage);
}

function addTablesListeners(t){
    t.addEventListener('click', addField);
}

function addListeners(){
    if(document.querySelector("#addTable")) document.querySelector("#addTable").addEventListener('click', addTable);
    var tables = document.querySelectorAll('.addField');
    if(tables) tables.forEach(addTablesListeners);
    var messages = document.querySelectorAll(".message");
    if(messages) messages.forEach(addMessagesListeners);
}

function addField(e){
    console.log(e.target.parentNode.childNodes[0]);
    e.preventDefault();
    let div = document.createElement("div");
    let input1 = document.createElement("input");
    let input2 = document.createElement("input");
    let input3 = document.createElement("input");
    let parentName = e.target.parentNode.childNodes[0].name.replace('[name]',  '');
    input1.placeholder = "Nazwa pola";
    input1.required = true;
    input1.name = parentName + "[" + document.querySelectorAll('.' + parentName.replace('[', '_').replace(']', '')).length + '][name]';
    input2.placeholder = "Typ pola";
    input2.name = parentName + "[" + document.querySelectorAll('.' + parentName.replace('[', '_').replace(']', '')).length + '][type]';
    input2.required = true;
    input3.placeholder = "Długość";
    input3.name = parentName + "[" + document.querySelectorAll('.' + parentName.replace('[', '_').replace(']', '')).length + '][length]';
    div.className = parentName.replace('[', '_').replace(']', '');
    div.style.marginLeft = "15px";
    div.style.marginTop = "5px";
    div.appendChild(input1);
    div.appendChild(input2);
    div.appendChild(input3);
    e.target.parentNode.insertBefore(div, e.target);
    e.preventDefault();
}

function addTable(e){
    let div = document.createElement("div");
    let input = document.createElement("input");
    let button = document.createElement("button");
    button.className = "addField";
    button.addEventListener('click', addField);
    input.type = "text";
    input.placeholder = "Nazwa tabeli";
    input.className = "db_table";
    input.name = "db_table[" + document.querySelectorAll(".db_table").length + "][name]";
    div.appendChild(input);
    div.appendChild(button);
    e.target.parentNode.insertBefore(div, e.target);
    e.preventDefault();
}

function init(){
    addListeners();
}

init();