<div id="page-wrapper">
    <script>
       /* tinymce.init({
            selector: 'textarea',
            height: 500
        });*/
    </script>

    <?php if (empty(validation_errors()) === false): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo validation_errors(); ?>
        </div>
    <?php endif; ?>

    <?php

    $action = "updateContract";
    if (is_null($contract)) {
        $action = "insertContract";
    }

    ?>

    <form action="<?= $action ?>" method="post">
        <input type="hidden" name="id"
               value="<?= is_null($contract) ? 0 : $contract->id ?>">
        <div class="form-group">
            <label>alias</label>
            <input type="text" name="alias" class="form-control"
                   value="<?= is_null($contract) ? ""
                       : $contract->alias ?>">
        </div>

        <div class="form-group">
            <label>name</label>
            <input type="text" name="name" class="form-control"
                   value="<?= is_null($contract) ? ""
                       : $contract->name ?>">
        </div>

        <textarea name="content" style="width:100%;height:500px">
        <?= is_null($contract) ? "" : $contract->content ?>
    </textarea>
        <button type="submit" class="btn btn-default">送出</button>
    </form>