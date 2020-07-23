<select name="city">
    <option value="0">&nbsp;</option>
    <?php foreach ($array['cityList'] as $key => $value) { ?>
        <option value="<?= $key ?>"><?= $value['city_name'] ?></option>
    <?php } ?>
</select>
