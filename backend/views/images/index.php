<?php
/**
 * @var $products
 * @var yii\web\View $this
 */
?>
    <h1>images/index</h1>

    <p>
        You may change the content of this page by modifying
        the file <code><?= __FILE__; ?></code>.
    </p>


<?php if (count($products) > 0): ?>
    <table class="table">
        <thead>
        <tr>
            <?php foreach (array_keys($products[0]) as $key): ?>
                <th scope="col"><?= $key; ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <?php foreach ($product as $key => $item): ?>
                    <td <?= ($key === 'id' ? 'scope="row"' : ''); ?>>
                        <?php if ($key === 'image'): ?>
                            <img src="<?= $item; ?>" alt="image"/>
                        <?php else: ?>
                            <?= $item; ?>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p> No query results</p>
<?php endif; ?>