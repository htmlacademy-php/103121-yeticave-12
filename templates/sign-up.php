<main>
    <?= $categories_content; ?>
    <form class="form container <?= isset($errors) ? 'form--invalid' : ''; ?>" action="sign-up.php" method="post" autocomplete="off">
      <h2>Регистрация нового аккаунта</h2>
      <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : ''; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= htmlspecialchars(get_post_val('email'), ENT_QUOTES) ?? ''; ?>">
        <span class="form__error"><?= $errors['email'] ?? '' ?></span>
      </div>
      <div class="form__item <?= isset($errors['password']) ? 'form__item--invalid' : ''; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= htmlspecialchars(get_post_val('password'), ENT_QUOTES) ?? ''; ?>">
        <span class="form__error"><?= $errors['password'] ?? '' ?></span>
      </div>
      <div class="form__item <?= isset($errors['name']) ? 'form__item--invalid' : ''; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= htmlspecialchars(get_post_val('name'), ENT_QUOTES) ?? ''; ?>">
        <span class="form__error"><?= $errors['name'] ?? '' ?></span>
      </div>
      <div class="form__item <?= isset($errors['message']) ? 'form__item--invalid' : ''; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= htmlspecialchars(get_post_val('message'), ENT_QUOTES) ?? ''; ?></textarea>
        <span class="form__error"><?= $errors['message'] ?? '' ?></span>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
</main>
