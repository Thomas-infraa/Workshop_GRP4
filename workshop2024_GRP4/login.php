<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="login.css" media="screen" type="text/css" />
    <style>
        /* Style pour le conteneur du champ de mot de passe */
        .password-container {
            position: relative;
            width: 80%;
        }

        /* Style pour l'input mot de passe */
        .password-container input[type="password"],
        .password-container input[type="text"] {
            width: 100%;
            padding-right: 40px; /* Espace pour l'œil */
            box-sizing: border-box; /* Pour inclure le padding dans la largeur */
        }

        /* Style pour l'icône œil */
        .password-container #togglePassword {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
<div id="container">
    <!-- zone de connexion -->
    <form action="verification.php" method="POST">
        <h1>Connexion</h1>
        
        <label><b>Email</b></label>
        <input type="text" placeholder="Entrer l'email" name="email" required>
        
        <label><b>Mot de passe</b></label>
        <div class="password-container">
            <input type="password" id="password" placeholder="Entrer le mot de passe" name="password" required>
            <span id="togglePassword">👁️</span>
        </div>
        
        <input type="submit" id='submit' value='Login'>

        <button onclick="window.location.href='inscription.php'" style="margin-top: 10px;">
            Register
        </button>

        
        <?php
        if (isset($_GET['erreur'])) {
            $err = $_GET['erreur'];
            if ($err == 1 || $err == 2) {
                echo "<p style='color:red'>mail ou mot de passe incorrect</p>";
            }
        }
        ?>
    </form>
</div>

<!-- Script pour afficher ou masquer le mot de passe -->
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        // Toggle le type d'input entre password et text
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Change l'icône de l'œil
        this.textContent = type === 'password' ? '👁️' : '🙈';
    });
</script>

</body>
</html>
