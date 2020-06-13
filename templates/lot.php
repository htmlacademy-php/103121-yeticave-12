<?= $categories_content; ?>
<section class="lot-item container">
    <h2><?= $lot['lot_name'] ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src=<?= $lot['image']; ?> width="730" height="548" alt=<?= $lot['lot_name']; ?>>
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot['category_name']; ?></span></p>
            <p class="lot-item__description"><?= $lot['description']; ?></p>
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
                Мин. ставка <span><?= format_price($lot['bet_step']); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>
