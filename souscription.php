<?php session_start();
include('header.html');
?>
<div class="container">
      <form class="form-horizontal" role="form" method="POST" action="/register">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h2>Register New User</h2>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="name">Name</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <div class="input-group-addon" style="width: 2.6rem"><i class="fa fa-user"></i></div>
                        <input type="text" name="name" class="form-control" id="name"
                               placeholder="John Doe" required autofocus>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-control-feedback">
                        <span class="text-danger align-middle">
                            <!-- Put name validation error messages here -->
                        </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="email">E-Mail Address</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <div class="input-group-addon" style="width: 2.6rem"><i class="fa fa-at"></i></div>
                        <input type="text" name="email" class="form-control" id="email"
                               placeholder="you@example.com" required autofocus>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-control-feedback">
                        <span class="text-danger align-middle">
                            <!-- Put e-mail validation error messages here -->
                        </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="password">Password</label>
            </div>
            <div class="col-md-6">
                <div class="form-group has-danger">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <div class="input-group-addon" style="width: 2.6rem"><i class="fa fa-key"></i></div>
                        <input type="password" name="password" class="form-control" id="password"
                               placeholder="Password" required>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-control-feedback">
                        <span class="text-danger align-middle">
                            <i class="fa fa-close"> Example Error Message</i>
                        </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 field-label-responsive">
                <label for="password">Confirm Password</label>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <div class="input-group-addon" style="width: 2.6rem">
                            <i class="fa fa-repeat"></i>
                        </div>
                        <input type="password" name="password-confirmation" class="form-control"
                               id="password-confirm" placeholder="Password" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-success"><i class="fa fa-user-plus"></i> Register</button>
            </div>
        </div>
    </form>
<fieldset>
        <form method="post" action="traitement.php" >

          <legend>Souscription</legend>
          <div class="col-sm-4">
            <label for="nom">nom</label>
            <input class="form-control" type="text" name="nom" value="" required/>
            </div>
            <div class="col-sm-4">
            <label for="prenom">prenom</label>
            <input class="form-control" type="text" name="prenom" value="" required/>
            </div>
            <div class="col-sm-4">
            <label for="email">E-mail</label>
            <input class="form-control" type="email" name="email" id="email" required/>
            </div>
            <div class="col-sm-4">
            <label for="password">mot de passe</label>
            <input class="form-control" type="password" name="password" required/>
            </div>
            <div class="col-sm-4">
            <label for="password">Confirmation mot de passe</label>
            <input class="form-control" type="password" name="conf_password"  required/>
            </div>
            <div class="col-sm-4">
            <label for="societe">societe</label>
            <input class="form-control" type="text" name="societe"  required/>
            </div>
            <div class="col-sm-4">
            <label for="localcommercial">Avez-vous un local commercial ?</label>
            <input class="form-control" type="radio" name="localcommercial" value="1" checked/>OUI
            <input class="form-control" type="radio" name="localcommercial" value="0" checked/>NON
            </div>
            <div class="col-sm-4">
            <label for="adresse">adresse</label>
            <input class="form-control" type="text" name="adresse"  required/>
            </div>
            <div class="col-sm-4">
            <label for="codepostal">code postal</label>
            <input class="form-control" type="text" name="codepostal"  required/>
            </div>
            <div class="col-sm-4">
            <label for="ville">ville</label>
            <input class="form-control" type="text" name="ville"  required/>
            </div>
            <div class="col-sm-4">
            <label for="telephone">Telephone</label>
            <input class="form-control" type="tel" name="telephone" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" />
            </div>
            <div class="col-sm-4">
          <input class="form-control btn-info" type="submit" value="OK" name="submit" />
          </div>
        </form>
</fieldset>
</div>
<?php
include('footer.html');
?>