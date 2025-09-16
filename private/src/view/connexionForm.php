<div class="authContainer">
    <!-- Form box -->
    <div class="box">
        <!-- Form -->
        <form method="POST" action="src/control/UserControl/loginUser.php">
            <h2>Connexion</h2>
            <!-- Input fields -->
            <div class="inputBox inputBoxOther">
                <input type="text" name="username" maxlength="26" pattern="[a-zA-Z0-9._]{3,26}" title="Seules les lettres, chiffres, '.' et '_' sont autorisés (entre 3 et 26 caractères)" autocomplete="off" required>
                <span>Pseudo</span>
                <i></i>
            </div>
            <div class="inputBox inputBoxOther">
                <input type="password" name="password" pattern="[A-Za-zÀ-ÿ0-9.]+" maxlength="15" title="Le mot de passe doit contenir des lettres, des chiffres et uniquement le symboles POINT" autocomplete="off" required>
                <span>Mot de passe</span>
                <i></i>
            </div>
            <!-- End of Input fields -->
            <input type="submit" name="connexion" value="Se connecter">
        </form>
        <!-- End of Form -->
    </div>
    <!-- End of Form box -->

    <div class="separator"></div>

    <!-- Form box -->
    <div class="box">
        <!-- Form -->
        <form method="POST" action="src/control/UserControl/registUser.php">
            <h2>Inscription</h2>
            <!-- Input fields -->
            <div class="boxIdentity">
                <div class="inputBox inputBoxOther">
                    <input type="text" name="username" maxlength="26" pattern="[a-zA-Z0-9._]{3,26}" title="Seules les lettres, chiffres, '.' et '_' sont autorisés (entre 3 et 26 caractères)" autocomplete="off" required>
                    <span>Pseudo</span>
                    <i></i>
                </div>
            </div>
            <div class="inputBox inputBoxOther">
                <input type="password" name="password" pattern="[A-Za-zÀ-ÿ0-9.]+" maxlength="15" title="Le mot de passe doit contenir des lettres, des chiffres et uniquement le symboles POINT" autocomplete="off" required>
                <span>Mot de passe</span>
                <i></i>
            </div>
            <div class="inputBox inputBoxOther">
                <input type="password" name="confirmPassword" pattern="[A-Za-zÀ-ÿ0-9.]+" maxlength="15" autocomplete="off" required>
                <span>Confirmer votre mot de passe</span>
                <i></i>
            </div>

            <!-- End of Input fields -->
            <input type="submit" name="inscription" value="S'inscrire">
        </form>
        <!-- End of Form -->
    </div>
    <!-- End of Form box -->
</div>