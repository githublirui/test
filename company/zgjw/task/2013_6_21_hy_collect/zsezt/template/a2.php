<?php
$province = getProvinceByid($_POST['province']);
$city = getCityByid($_POST['city']);
?>
<select class="province address" name="province">
    <option value="0">请选择</option>
    <?php foreach (getAllProvinces() as $pro_l) { ?>
        <option <?php echo $province && $province['id'] == $pro_l['id'] ? 'selected=selected' : '' ?> value="<?php echo $pro_l['id'] ?>"><?php echo $pro_l['province'] ?></option>
    <?php } ?>
</select>
省
<select class="city address" name="province">
    <option value="0">请选择</option>
    <?php if ($province) { ?>
        <?php foreach (getCitysByProId($province['id']) as $city_l) { ?>
            <option <?php echo $city && $city['id'] == $city_l['id'] ? 'selected=selected' : '' ?> value="<?php echo $city_l['id'] ?>"><?php echo $city_l['city'] ?></option>
        <?php } ?>
    <?php } ?>
</select>
市