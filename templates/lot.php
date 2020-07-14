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
                    <?= get_time_range(htmlspecialchars($lot['finish_date'], ENT_QUOTES))[0] >= 1
                        ? ''
                        : 'timer--finishing';
                    ?>
                ">
                    <?= implode(':' ,get_time_range(htmlspecialchars($lot['finish_date'], ENT_QUOTES))); ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                    <span class="lot-item__amount">Текущая цена</span>
                    <span class="lot-item__cost"><?= format_price(htmlspecialchars($lot['price'], ENT_QUOTES)); ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                    Мин. ставка <span><?= format_price(htmlspecialchars($lot['bet_step'], ENT_QUOTES)); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
