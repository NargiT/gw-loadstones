function init() {
    $("#prixachat_destructeur").keyup(function(e) {
        calculate('destructeur');
    });
//destructeur_prixAchat.addEventListener("input", function(e){calculate("destructeur");}, true);
}

function calculate(magnetite) {
    console.log(magnetite);
    switch (magnetite) {
        case "destructeur":
            var value = Number($('#prixachat_destructeur').val());
            if (isNaN(value)) {
                value = 0;
            }
            value += 10;
            $('#prixvente_destructeur').val(value);
            break;
        case "chargé":
			
            break;
    }
}

$(document).ready(function(){
    init();
})

function MakeTransparent(evt) {
    evt.target.setAttributeNS(null,"opacity","0.5");
}

function MakeOpaque(evt) {
    evt.target.setAttributeNS(null,"opacity","1");
}