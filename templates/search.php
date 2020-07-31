<main>
    <?= $categories_content; ?>
    <div class="container">
      <section class="lots">
        <?php if ($lots): ?>
        <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($search, ENT_QUOTES); ?></span>»</h2>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src=<?= htmlspecialchars($lot['image'], ENT_QUOTES); ?> width="350" height="260" alt=<?= htmlspecialchars($lot['name'], ENT_QUOTES); ?>>
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= htmlspecialchars($lot['category'], ENT_QUOTES); ?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $lot['id']?>"><?= htmlspecialchars($lot['name'], ENT_QUOTES); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= format_price($lot['price']); ?></span>
                        </div>
                        <div class="lot__timer timer
                            <?= get_time_range($lot['finish_date'])[0] >= 1
                                ? ''
                                : 'timer--finishing';
                            ?>
                        ">
                            <?= implode(':' ,get_time_range($lot['finish_date'])); ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
            <h2>Ничего не найдено по вашему запросу</h2>
        <?php endif; ?>
      </section>
      <?=include_template('pagination.php',
        [
            'pages' => $pages,
            'pages_count' => $pages_count,
            'current_page' => $current_page,
            'search' => $search
        ]
      );?>
    </div>
</main>
