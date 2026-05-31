<?php
$isLoggedIn = ($isLoggedIn ?? 'false') === 'true';
$errors = json_decode($errors ?? '[]', true);
$success = ($success ?? 'false') === 'true';
?>

<?php if ($isLoggedIn): ?>

    <div class="page-heading">
        <div class="page-heading__text">
            <h1 class="page-heading__title">Contact</h1>
            <p class="page-heading__subtitle">Envoyez-nous un message, nous vous répondrons rapidement</p>
        </div>
    </div>

    <?php if ($success): ?>

        <div class="alert alert--success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Votre message a bien été envoyé. Merci !
        </div>

    <?php else: ?>

        <?php foreach ($errors as $err): ?>
            <div class="alert alert--error"><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>

        <div class="contact-grid">

            <div class="card">
                <div class="card__header">
                    <div>
                        <div class="card__title">Envoyer un message</div>
                        <div class="card__subtitle">Remplissez le formulaire ci-dessous</div>
                    </div>
                </div>

                <form class="form form--compact" method="POST" action="/contact">

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="firstname">Prénom</label>
                            <input class="form-control" type="text" name="firstname" id="firstname"
                                   value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="lastname">Nom</label>
                            <input class="form-control" type="text" name="lastname" id="lastname"
                                   value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" type="email" name="email" id="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="subject">Sujet</label>
                        <input class="form-control" type="text" name="subject" id="subject"
                               value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="message">Message</label>
                        <textarea class="form-control" name="message" id="message" rows="5" required
                                  placeholder="Décrivez votre demande..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="button button--primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                        </svg>
                        Envoyer le message
                    </button>

                </form>
            </div>

            <div class="contact-infos">
                <div class="card">
                    <div class="stat-card__icon stat-card__icon--blue" style="margin-bottom:12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                    </div>
                    <div class="card__title">Email</div>
                    <div class="card__subtitle"><?php echo defined('MAIL_CONTACT') ? htmlspecialchars(MAIL_CONTACT) : 'contact@budgie.fr' ?></div>
                </div>

                <div class="card">
                    <div class="stat-card__icon stat-card__icon--grey" style="margin-bottom:12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="card__title">Délai de réponse</div>
                    <div class="card__subtitle">Sous 24–48h ouvrées</div>
                </div>
            </div>

        </div>

    <?php endif; ?>

<?php else: ?>

    <div class="card card--auth">

        <div class="logo-wrapper logo-wrapper--sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
            </svg>
        </div>

        <div>
            <h1 class="page-title">Nous contacter</h1>
            <p class="page-subtitle">Envoyez-nous un message</p>
        </div>

        <?php if ($success): ?>

            <div class="alert alert--success">Votre message a bien été envoyé. Merci !</div>
            <div class="footer-links">
                <a href="/login" class="link link--blue">← Retour à la connexion</a>
            </div>

        <?php else: ?>

            <?php foreach ($errors as $err): ?>
                <div class="alert alert--error"><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>

            <form class="form form--compact" method="POST" action="/contact">

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="firstname-g">Prénom</label>
                        <input class="input" type="text" name="firstname" id="firstname-g"
                               value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="lastname-g">Nom</label>
                        <input class="input" type="text" name="lastname" id="lastname-g"
                               value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email-g">Email</label>
                    <input class="input" type="email" name="email" id="email-g"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="subject-g">Sujet</label>
                    <input class="input" type="text" name="subject" id="subject-g"
                           value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="message-g">Message</label>
                    <textarea class="input" name="message" id="message-g" rows="4" required
                              placeholder="Votre message..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>

                <button class="button button--full" type="submit">Envoyer</button>

            </form>

            <div class="footer-links">
                <span>Déjà un compte ?</span>
                <a href="/login" class="link">Se connecter</a>
            </div>

        <?php endif; ?>

    </div>

<?php endif; ?>