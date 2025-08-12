var tabButtons = document.querySelectorAll(".item2 button");
var tabPanels = document.querySelectorAll(".item3 .tabPanel");

function showPanel(panelIndex, colorCode) {
    tabButtons.forEach(function (node) {
        node.style.background = "";
        node.style.color = "";
    });
    tabButtons[panelIndex].style.background = colorCode; 
    tabButtons[panelIndex].style.color = "white";
    tabPanels.forEach(function (node) {
        node.style.display = "none";
    });
    tabPanels[panelIndex].style.display = "block";
   
}

showPanel(0, 'linear-gradient(90deg, #2d3a5a 80%, #1abc9c 100%)');