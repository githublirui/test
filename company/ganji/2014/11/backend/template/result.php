<form>
    <?php if ($this->flag) { ?>
        <label style="color:green;">操作成功</label>
    <?php } else { ?>
        <label style="color:red;">操作失败</label>
    <?php } ?>
    <?php if (!empty($this->errMsg)) { ?>
        <label><?php echo $this->errMsg; ?></label>
    <?php } ?>
</form>