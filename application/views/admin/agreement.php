<div id="page-wrapper">
    <script>
        tinymce.init({
            selector: 'textarea',
            height: 500
        });
    </script>

    <?php if (empty(validation_errors()) === false): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo validation_errors(); ?>
        </div>
    <?php endif; ?>

    <?php

    $action = "updateAgreement";
    if (is_null($agreement)) {
        $action = "insertAgreement";
    }

    ?>

    <form action="<?= $action ?>" method="post">
        <input type="hidden" name="id"
               value="<?= is_null($agreement) ? 0 : $agreement->id ?>">
        <div class="form-group">
            <label>alias</label>
            <input type="text" name="alias" class="form-control"
                   value="<?= is_null($agreement) ? ""
                       : $agreement->alias ?>">
        </div>

        <div class="form-group">
            <label>name</label>
            <input type="text" name="name" class="form-control"
                   value="<?= is_null($agreement) ? ""
                       : $agreement->name ?>">
        </div>

        <textarea name="content">
        <?= is_null($agreement) ? "" : $agreement->content ?>
    </textarea>
        <button type="submit" class="btn btn-default">submit</button>
    </form>