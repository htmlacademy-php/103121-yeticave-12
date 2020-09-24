<main>
    <?= $categories_content; ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <?php if(isset($bets)): ?>
            <table class="rates__list">
                <?php foreach($bets as $bet): ?>
                    <tr class="rates__item">
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src=<?= htmlspecialchars($bet['image'], ENT_QUOTES); ?> width="54" height="40" alt=<?= htmlspecialchars($bet['lot_name'], ENT_QUOTES) ?>>
                            </div>
                            <h3 class="rates__title"><a href="lot.php?id=<?= $bet['id'] ?>"><?= htmlspecialchars($bet['lot_name'], ENT_QUOTES) ?></a></h3>
                        </td>
                        <td class="rates__category">
                            <?= htmlspecialchars($bet['category_name'], ENT_QUOTES) ?>
                        </td>
                        <td class="rates__timer">
                            <div class="timer  <?= get_time_range($bet['finish_date'])[0] >= 1
                                    ? ''
                                    : 'timer--finishing';
                                ?>
                            ">
                            <?= implode(':' ,get_time_range($bet['finish_date'])); ?>
                        </td>
                        <td class="rates__price">
                            <?= format_price($bet['bet_price']) ?>
                        </td>
                        <td class="rates__time">
                            <?= get_time_passed($bet['bet_date']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </section>
</main>
