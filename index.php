<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Rich Ricasso</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ma Page PHP</title>
    </head>
    <body>
        <header>
            <nav>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="catalogue.php">Catalogue</a></li>
                <img src="https://cdn-icons-png.flaticon.com/512/2182/2182890.png" alt="Logo de Rich Ricasso" style="height:40px;">
            </nav>
        </header>

        <!-- Introduction -->
        <div class="introduction-parcours">
            <h1>Rich Ricasso</h1>

            <p class="introduction">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse convallis libero arcu, at cursus elit elementum sit amet. Donec blandit eu dui nec viverra. Duis hendrerit mattis ipsum ac feugiat. Praesent pretium magna elit, sed mollis nisi bibendum nec. Nulla sit amet ante at eros tristique auctor at ultricies augue. Sed suscipit, velit quis scelerisque consequat, lacus ligula volutpat arcu, ut dignissim dui mi ut lectus. Duis sagittis nisl in dui placerat gravida. Duis cursus pharetra tellus, pellentesque consectetur orci fringilla non.</p>
            <p class="parcours">In nulla mauris, elementum vitae magna a, vestibulum dapibus nulla. In consequat dapibus eros. Donec bibendum pulvinar porta. Ut tincidunt ligula lorem, in tempus mauris auctor quis. Pellentesque tristique magna non nibh semper facilisis dignissim a nisl. Etiam id urna volutpat massa fringilla vestibulum. Sed nibh erat, tincidunt in pellentesque sit amet, vestibulum in enim. Morbi a facilisis diam, eget semper ex. Pellentesque fermentum, ante scelerisque bibendum suscipit, tortor urna mattis enim, id vehicula dolor nisi porttitor dui. Sed eleifend lobortis magna vel scelerisque. Quisque nec malesuada enim. Sed vitae fringilla risus. Aliquam erat volutpat.</p>
        </div>

        <!-- Présentation des pièces phares -->
        <main>
            <h2>Contenu Principal</h2>
            <p>Ceci est le contenu principal de la page.</p>
        </main>

        <!-- Bas de page / Infolettre -->
        <footer>
            <div class="infolettre">
                <h2>Inscrivez-vous à notre infolettre</h2>
                <form action="subscribe.php" method="post">
                    <label for="email">Adresse e-mail:</label>
                    <input type="email" id="email" name="email" required>
                    <button type="submit">S'inscrire</button>
                </form>
            </div>
        </footer>
    </body>
</html>
