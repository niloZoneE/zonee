<?php session_start();
include('header.html');
?>

<fieldset>
        <form method="post" action="traitement.php" >

          <legend>Souscription</legend>

          <p>
            <label for="civilite">civilite</label>
            <input type="radio" name="civilite" value="M" >M
            <input type="radio" name="civilite" value="F" >F
          </p>

          <p>
            <label for="nom">nom</label><input type="text" name="nom" value="" required/>
          </p>

          <p>
            <label for="prenom">prenom</label><input type="text" name="prenom" value="" required/>
          </p>

          <p>
            <label for="email">E-mail</label><input type="email" name="email" id="email" required/>
          </p>

          <p>
            <label for="password">mot de passe</label><input type="password" name="password" required/>
          </p>

          <p>
            <label for="password">Confirmation mot de passe</label><input type="password" name="conf_password"  required/>
          </p>

          <p>
            <label for="naissance">date de naissance</label><input type="date" name="naissance" required/>
          </p>

          <p>
            <label for="societe">societe</label><input type="text" name="societe"  required/>
          </p>

          <p>
            <label for="localcommercial">Avez-vous un local commercial ?</label>
            <input type="radio" name="localcommercial" value="1" checked/>OUI
            <input type="radio" name="localcommercial" value="0" checked/>NON
          </p>

          <p>
            <label for="adresse">adresse</label><input type="text" name="adresse"  required/>
          </p>

          <p>
            <label for="codepostal">code postal</label> : <input type="text" name="codepostal"  required/>
          </p>

          <p>
            <label for="ville">ville</label><input type="text" name="ville"  required/>
          </p>

          <p>
            <label for="pays">pays</label><input type="text" name="pays"  required/>
          </p>

          <p>
            <label for="telephone">Telephone</label><input type="tel" name="telephone" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" />
          </p>

          <input type="submit" value="OK" name="submit" />

        </form>
</fieldset>
<?php
include('footer.html');
?>