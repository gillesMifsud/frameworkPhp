<?= $renderer->render('header') ?>

<div class="row">
    <p>Liste des articles</p>

    <ul>
        <li><a href="<?= $router->generateUri('blog.show', ['slug' => 'azeaeez0-7zyeug']) ?>">Article</a></li>
        <li>Article 1</li>
        <li>Article 1</li>
        <li>Article 1</li>
        <li>Article 1</li>
        <li>Article 1</li>
    </ul>
</div>

<?= $renderer->render('footer') ?>