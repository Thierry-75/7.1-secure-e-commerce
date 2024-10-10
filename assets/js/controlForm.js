window.onload = () => {
  let connexion_form = document.querySelector("#connexion_form");
  if (connexion_form) {
    let affichage = document.querySelector("#message_form_connexion");
    let indication = "Indiquez votre Email et votre mot de passe";
    story_show(affichage, indication);
  
    let inputEmail = connexion_form.querySelector("#inputEmail");
    let erreur_email = connexion_form.querySelector("#emailSmall");
    inputEmail.addEventListener("focus", function () {
      clearEmailLogin(this, affichage, erreur_email);
    });
    inputEmail.addEventListener("change", function () {
      controlEmailLogin(this, affichage, erreur_email);
    });
    inputEmail.addEventListener("blur", function () {
      resultatEmailLogin(this, erreur_email);
    });

    let inputPassword = connexion_form.querySelector("#inputPassword");
    let erreur_passw = connexion_form.querySelector("#passwordSmall");
    inputPassword.addEventListener("focus", function () {
      clearPasswordLogin(this, affichage, erreur_passw);
    });
    inputPassword.addEventListener("change", function () {
      controlPasswordLogin(this, affichage, erreur_passw);
    });
    inputPassword.addEventListener("blur", function () {
      resultatPasswordLogin(this, erreur_passw);
    });

    let remember_me = connexion_form.querySelector("#remember_me");
    let erreur_remember = connexion_form.querySelector("#smallSubmit");
    remember_me.addEventListener("focus", function () {
      clearRememberLogin(this);
    });
    remember_me.addEventListener('click',function(){
        controlRememberLogin(this,erreur_remember);
    })
    remember_me.addEventListener("blur", function () {
      resultatRememberLogin(this, affichage, erreur_remember);
    });

    let formSubmitLogin = document.querySelector('#formSubmitLogin');
    formSubmitLogin.addEventListener('click',function(event){
        let inputs = connexion_form.getElementsByTagName('input');

        let compteur = 0;
        let champsSuccess = [];
        let nbBordure=0;
        for(var i=0; i < inputs.length; i++){
            if(inputs[i].type=='email' || inputs[i].type=='password'){
                champsSuccess[i] = inputs[i];
                if(inputs[i].value ==""){
                    alert_submit(inputs[i]);
                    compteur++;
                }
            }
        }
      for(var j=0; j < champsSuccess.length; j++){
        if(champsSuccess[j].classList.contains('border-green-600')){
            nbBordure++;
        }
      }
      if(!remember_me.checked){
        alert_submit(remember_me);
        erreur_remember.classList.remove("text-xs","font-light","text-gray-500","dark:text-gray-300");
        erreur_remember.classList.add("text-xs", "text-red-600", "text-center", "italic");
      }
      if(!remember_me.checked || !compteur ==0 || !champsSuccess.length ==nbBordure){
        let mot ='Votre saisie n\'est pas conforme !'
        story_show(affichage,mot);
        event.preventDefault();
        event.stopImmediatePropagation();
        return false;
      }
    })
  }
};

const clearEmailLogin = function (champ, message, erratum) {
  let mot = "Indiquez votre adresse email";
  story_show(message, mot);
  inputEmail.value = "";
  original_border(champ);
  erratum.innerHTML = "";
};
const clearPasswordLogin = function (champ, message, erratum) {
  let mot = "Indiquez votre mot de passe";
  story_show(message, mot);
  inputPassword.value = "";
  original_border(champ);
  erratum.innerHTML = "";
};

const clearRememberLogin = function (champ) {
  if (!champ.checked) {
    alert_submit(champ);
  } else {
    original_border(champ);
  }
};

const controlEmailLogin = function (champ, message, erratum) {
  let email_regexp = new RegExp(
    /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/
  );
  if (champ.value.match(email_regexp)) {
    success_submit(champ);
  } else {
    alert_submit(champ);
    let mot = "";
    story_show(message, mot);
    erratum.innerHTML = "Adresse email erronée, ex: exemple@email.com";
  }
};

const controlPasswordLogin = function (champ, message, erratum) {
  let password_regexp = new RegExp(
    "^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{10,12}$"
  );
  if (champ.value.match(password_regexp)) {
    success_submit(champ);
  } else {
    alert_submit(champ);
    let mot = "";
    story_show(message, mot);
    erratum.innerHTML =
      "Champ invalide 10 à 12 caractères: A-Za-z0-9#?!@$ %^&*-";
  }
};

const controlRememberLogin = function(champ,erratum){
    if(!champ.checked){
        alert_submit(champ);
    }else{
        success_submit(champ);
        let erreur = ' Se souvenir de moi';
        cool_show(erratum,erreur);
    }

}

const resultatEmailLogin = function (champ, erratum) {
  if (champ.value == "") {
    alert_submit(champ);
    erratum.innerHTML = "";
  }
};

const resultatPasswordLogin = function (champ, erratum) {
  if (champ.value == "") {
    alert_submit(champ);
    erratum.innerHTML = "";
  }
};
const resultatRememberLogin = function (champ, message, erratum) {
  if (!champ.checked) {
    alert_submit(champ);
    let mot = "Veuillez à cocher cette case SVP";
    let erreur = "Vérifiez votre saisie !";
    story_show(erratum, mot);
    story_show(message, erreur);
  } else {
    success_submit(champ);
    let mot = "Se souvenir de moi";
    let erreur = "";
    cool_show(erratum, mot);
    story_show(message, erreur);
  }
};
/*----------------functions-----------------*/
const story_show = function (message, mot) {
  message.innerHTML = mot;
  message.classList.remove(
    "text-xs",
    "font-light",
    "text-gray-500",
    "dark:text-gray-300"
  );
  message.classList.add("text-xs", "text-red-600", "text-center", "italic");
};

const cool_show = function (message, mot) {
  message.innerHTML = mot;
  message.classList.remove("text-xs", "text-red-600", "text-center", "italic");
  message.classList.add(
    "text-xs",
    "font-light",
    "text-gray-500",
    "dark:text-gray-300"
  );
};

const alert_submit = function (champ) {
  champ.classList.remove("border-gray-300");
  champ.classList.remove("border-green-600");
  champ.classList.add("border-red-600");
};
const success_submit = function (champ) {
  champ.classList.remove("border-gray-300");
  champ.classList.remove("border-red-600");
  champ.classList.add("border-green-600");
};

const original_border = function (champ) {
  champ.classList.remove("border-red-600");
  champ.classList.remove("border-green-600");
  champ.classList.add("border-gray-300");
};

const clearMessage = function (message) {
  var mot = "";
  story_show(message, mot);
};
