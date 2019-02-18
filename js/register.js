/* globals _alert */
let country_select = document.getElementsByName('country')[0];
let region_select = document.getElementsByName('region')[0];

let traitement_add_user = (form) => {
    let data = new FormData();
    data.append('lastname', document.getElementsByName('lastname')[0].value);
    data.append('firstname', document.getElementsByName('firstname')[0].value);
    data.append('birthday', document.getElementsByName('birthday')[0].value);
    data.append('email', document.getElementsByName('email')[0].value);
    data.append('work', document.getElementsByName('work')[0].value);
    data.append('country', country_select.value);
    data.append('region', (country_select.value === 'FR' ? region_select.value : 0));
    data.append('sex', ''); //permet d'éviter les bugs si le champs 'sex' n'est pas complété
    let radio_sex = document.getElementsByName('sex');
    for (let i = 0, length = radio_sex.length; i < length; i++) {
        if (radio_sex[i].checked) {
            data.set('sex', radio_sex[i].value);
            break;
        }
    }
    data.append('password', document.getElementsByName('password')[0].value);
    data.append('password_confirm', document.getElementsByName('password_confirm')[0].value);
    if (form.id === '_form_user')
        data.append('user_id', document.getElementsByName('user_id')[0].value);
    let xhr = new XMLHttpRequest();
    xhr.addEventListener('loadend', () => {
        if (xhr.status === 200 && xhr.response) {
            let obj = JSON.parse(xhr.response);
            let paragraph = _alert.getElementsByTagName('p')[0];
            let alert_color;
            _alert.style.right = '20px';
            _alert.style.display = 'block';
            _alert.style.opacity = 1;
            if (obj.create === true) {
                paragraph.innerHTML =
                    '<b>Votre compte a bien été créé.</b>' +
                    '<br/><br/>Vous allez recevoir un mail de confirmation dans quelques instants.' +
                    '<br/>Redirection en cours...';
                alert_color = 'success';
            } else if (obj.update === true) {
                paragraph.innerHTML =
                    '<b>L\'utilisateur a bien été modifié.</b>' +
                    '<br/>Redirection en cours...';
                alert_color = 'change';
            } else {
                if (typeof obj.create !== "undefined")
                    paragraph.innerText = obj.create;
                else if (typeof obj.update !== "undefined")
                    paragraph.innerText = 'La mise à jour du profil utilisateur a échoué';
                alert_color = 'error';
            }
            _alert.style.backgroundColor = 'var(--' + alert_color + ')';
             if (obj.create === true || obj.update === true) {
                 setTimeout(() => {
                     _alert.style.opacity = 0;
                     if (obj.create || obj.update) {
                         window.location.href = 'index.php'
                     }
                 }, 5000);
             }
        }
    });
    xhr.open(form.method, form.action + '?type=create');
    xhr.send(data);
};

country_select.addEventListener('click', () => {
    /* on désactive la zone "région" si le pays est différent de France */
    region_select.disabled = (country_select.value !== 'FR');
});