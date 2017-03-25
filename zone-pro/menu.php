<?php
if(!empty($_POST['supprime_connexion'])) {
    ProtectEspace::deleteJeton($_POST['id_jeton']);
}
    echo '<center>
        <div class="container">
        <h2>Liste des Connexions</h2>
        <br />
            <table width="70%" align="center">
                <tr>
                    <td align="center" width="40%" class="titre_form">Date</td>
                    <td align="center" class="titre_form">Adresse IP</td>
                    <td align="center" width="20%" class="titre_form">Action</td>
                </tr>
                    '.ProtectEspace::compteJeton($_SESSION['id']).'
                    '.ProtectEspace::listeJeton($_SESSION['id']).'
            </table>
            </div>
            </center>';
?>
