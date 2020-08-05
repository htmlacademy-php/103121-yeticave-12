<main>
    <?= $categories_content; ?>
    <section class="lot-item container">
        <h2><?= $lot['lot_name'] ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= htmlspecialchars($lot['image'], ENT_QUOTES); ?>" width="730" height="548" alt="<?= htmlspecialchars($lot['lot_name'], ENT_QUOTES); ?>">
                </div>
                <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['category_name'], ENT_QUOTES); ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($lot['description'], ENT_QUOTES); ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer
                        <?= get_time_range($lot['finish_date'])[0] >= 1
                            ? ''
                            : 'timer--finishing';
                        ?>
                    ">
                        <?= implode(':' ,get_time_range($lot['finish_date'])); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= format_price($lot['price']); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                        Мин. ставка <span><?= format_price($lot['bet_step'] + $lot['price']); ?></span>
                        </div>
                    </div>
                    <?php if ((isset($_SESSION['user']))
                            && ($_SESSION['user']['id'] !== $lot['author_id'])
                            && ($_SESSION['user']['id'] !== $lot['bet_author_id'])
                            && (strtotime($lot['finish_date']) > time()))
                    :?>
                        <form class="lot-item__form" action="lot.php?id=<?= $lot['id']?>" method="post" autocomplete="off">
                            <p class="lot-item__form-item form__item <?= isset($errors['cost']) ? 'form__item--invalid' : ''; ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost" value="<?= isset($errors['cost']) ? htmlspecialchars(getPostVal('cost', ENT_QUOTES)) : ''; ?>">
                                <span class="form__error"><?= $errors['cost'] ?? '' ?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    <?php endif ?>
                </div>
                <?php if ($lot_bets): ?>
                    <div class="history">
                        <h3>История ставок (<span><?= count($lot_bets) ?></span>)</h3>
                        <table class="history__list">
                            <?php foreach ($lot_bets as $lot_bet): ?>
                                <tr class="history__item">
                                    <td class="history__name"><?= htmlspecialchars($lot_bet['name'], ENT_QUOTES) ?></td>
                                    <td class="history__price"><?= format_price($lot_bet['price']); ?></td>
                                    <td class="history__time"><?= getTimePassed($lot_bet['date']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </section>
</main>
