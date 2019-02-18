<footer>
    <nav>
        <a href="#">Facebook</a>
        <a href="#">Twitter</a>
        <a href="#">Instagram</a>
        <a href="#">Pinterest</a>
    </nav>
    <p>
        Nous sommes une entreprise artisanale depuis 1923, nous sommes spécialisés dans l'un des fruits les plus
        efficaces pour notre santé : le citron.<br/>
        Retrouvez nos desserts et boissons à base de ce merveilleux agrume, ainsi que des articles et recettes dans
        notre blog.
    </p>
    <p>
        <strong>Adresse :</strong>
        11 rue des Développeurs<br/>
        59000 LILLE<br/>
        <br/>
        08 36 65 65 65 (Standard)<br/>
        06 21 22 23 24 (Mobile)<br/>
        03 20 15 15 15 (fax)<br/>
    </p>
</footer>
<script>
    /* globals _btn_nav */
    btn_nav.addEventListener('click', function () {
        document.body.classList.toggle('nav_closed');
        btn_nav.style.backgroundColor
    })
</script>
<?php require_once 'alert_cookie.html';