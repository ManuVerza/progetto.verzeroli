function redTax(){ // Funzione per calcolare l'importo effettivo da pagare totale
    var reddito = parseInt(document.getElementById('RAL').value);

    var sc1 = calcolaTax(6)/100;
    var sc2 = calcolaTax(12)/100;
    var sc3 = calcolaTax(18)/100;
    var sc4 = calcolaTax(24)/100;
    var sc5 = calcolaTax(30)/100;
    var sc6 = calcolaTax(36)/100;
    var sc7 = calcolaTax(42)/100;

    if(reddito <= 15000){
        return reddito*(sc1);
    }
    if(reddito <= 30000){
        return (15000*sc1) + ((reddito-15000)*sc2);
    }
    if(reddito <= 45000){
        return (15000*sc1) + (15000*sc2) + ((reddito-30000)*sc3);
    }
    if(reddito <= 60000){
        return (15000*sc1) + (15000*sc2) + (15000*sc3) + ((reddito-45000)*sc4);
    }
    if(reddito <= 75000){
        return (15000*sc1) + (15000*sc2) + (15000*sc3) + (15000*sc4) + ((reddito-60000)*sc5);
    }
    if(reddito <= 90000){
        return (15000*sc1) + (15000*sc2) + (15000*sc3) + (15000*sc4) + (15000*sc5) + ((reddito-75000)*sc6);
    }
    if(reddito > 90000){
        return (15000*sc1) + (15000*sc2) + (15000*sc3) + (15000*sc4) + (15000*sc5) + (15000*sc6) + ((reddito-90000)*sc7);
    }

}

function regionCheck(){ // Funzione che restituisce il valore di modifica per la percentuale
    var reg = document.querySelector('input[name="regione"]:checked').value;
    
    if (reg == "Grifondoro") {
        return -1;
      } else if (reg == "Serpeverde") {
        return 0;
      } else if (reg == "Corvonero") {
        return 1;
      } else if (reg == "Tassorosso") {
        return 2;
      } else {
        return 100;
      }
}

function mensCheck(){ // Come regionCheck() ma per la mensilità, maggiore il num mensilità, < il guadagno mensile sullo stesso RAL, ergo tasse agevolate
    var mens = document.querySelector('input[name="mensilita"]:checked').value;
    if (mens == 12) {
        return 0;
      } else if (mens == 13) {
        return -1;
      } else if (mens == 14) {
        return -2;
      } else {
        return 100;
      }
}

function calcolaTax(scV) { //Funzione che prende in input come parametro un valore dall'array degli scaglioni, aggiunge i modificatori e restituisce la percentuale da utilizzare

    var figli = parseInt(document.getElementById("inputFigli").value);
    
    var taxV = scV-regionCheck()-mensCheck()-figli;
    
    if(taxV<0)taxV=0; //impedisce alla % di essere <0

    return taxV;

}

function displayRisultati(event){ //Da Implementare, prende i risultati da redTax() e li stampa nel div risultati (v. html)
    event.preventDefault();
    var risTax = redTax();
    var reddito = parseInt(document.getElementById('RAL').value); 
    var typetax = typeof risTax;
    var typeRed = typeof reddito;
    var netto = reddito-risTax;
    document.getElementById("risultati1").innerText= "Da versare (€): " + risTax;
    document.getElementById("risultati2").innerText= "Netto rimanente (€): " + netto;
    
  
}


