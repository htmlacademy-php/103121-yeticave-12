<main>
    <?= $categories_content; ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <?php if(isset($bets)): ?>
            <table class="rates__list">
                <?php foreach($bets as $bet): ?>
                    <tr class="rates__item
                        <?php if($bet['user_id'] === $bet['winner_id']): ?>
                            rates__item--win
                        <?php elseif(get_time_difference($bet['finish_date']) <= 0): ?>
                            rates__item--end
                        <?php endif; ?>
                    ">
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src=<?= htmlspecialchars($bet['image'], ENT_QUOTES); ?> width="54" height="40" alt=<?= htmlspecialchars($bet['lot_name'], ENT_QUOTES) ?>>
                            </div>
                            <div>
                                <h3 class="rates__title"><a href="lot.php?id=<?= $bet['id'] ?>"><?= htmlspecialchars($bet['lot_name'], ENT_QUOTES) ?></a></h3>
                                <?php if($bet['user_id'] === $bet['winner_id']): ?><p><?= $bet['author_contacts']; ?></p><?php endif; ?>
                            </div>
                        </td>
                        <td class="rates__category">
                            <?= htmlspecialchars($bet['category_name'], ENT_QUOTES) ?>
                        </td>
                        <td class="rates__timer">
                            <div class="timer
                                <?php if($bet['user_id'] === $bet['winner_id']): ?>
                                    timer--win
                                <?php elseif(get_time_difference($bet['finish_date']) <= 0): ?>
                                    timer--end
                                <?php elseif(get_time_range($bet['finish_date'])[0] < 1): ?>
                                    timer--finishing
                                <?php endif; ?>
                            ">
                                <?php if($bet['user_id'] === $bet['winner_id']): ?>
                                    Ставка выиграла
                                <?php elseif(get_time_difference($bet['finish_date']) <= 0): ?>
                                    Торги окончены
                                <?php else: ?>
                                    <?= implode(':' ,get_time_range($bet['finish_date'])); ?>
                                <?php endif; ?>
                            </div>
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
