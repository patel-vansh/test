<?php
$paytm_settings = $this->db->get_where('settings', array('key' => 'paytm_keys'))->row()->value;
$paytm = json_decode($paytm_settings);
?>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title"><p><?php echo get_phrase('setup_paytm_settings'); ?></p></h4>
            <form class="" action="<?php echo site_url('addons/paytm/update_settings'); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label><?php echo get_phrase('PAYTM_MODE'); ?></label>
                    <select class="form-control select2" data-toggle="select2" id = "PAYTM_MODE" name="PAYTM_MODE">
                        <option value="production"> <?php echo get_phrase('Production');?></option>
                    </select>
                </div>

                <div class="form-group">
                    <label><?php echo get_phrase('PAYTM_MERCHANT_KEY'); ?></label>
                    <input type="text" name="PAYTM_MERCHANT_KEY" class="form-control" value="<?php echo htmlspecialchars($paytm[0]->PAYTM_MERCHANT_KEY);?>" required />
                </div>

                <div class="form-group">
                    <label><?php echo get_phrase('PAYTM_MERCHANT_MID'); ?></label>
                    <input type="text" name="PAYTM_MERCHANT_MID" class="form-control" value="<?php echo htmlspecialchars($paytm[0]->PAYTM_MERCHANT_MID);?>" required />
                </div>

                <div class="form-group">
                    <label><?php echo get_phrase('PAYTM_MERCHANT_WEBSITE'); ?></label>
                    <input type="text" name="PAYTM_MERCHANT_WEBSITE" class="form-control" value="<?php echo htmlspecialchars($paytm[0]->PAYTM_MERCHANT_WEBSITE);?>" required />
                </div>

                <div class="form-group">
                    <label><?php echo get_phrase('INDUSTRY_TYPE_ID'); ?></label>
                    <input type="text" name="INDUSTRY_TYPE_ID" class="form-control" value="<?php echo htmlspecialchars($paytm[0]->INDUSTRY_TYPE_ID);?>" required />
                </div>

                <div class="form-group">
                    <label><?php echo get_phrase('CHANNEL_ID'); ?></label>
                    <input type="text" name="CHANNEL_ID" class="form-control" value="<?php echo htmlspecialchars($paytm[0]->CHANNEL_ID);?>" required />
                </div>

                <div class="row justify-content-md-center">
                    <div class="form-group col-md-6">
                        <button class="btn btn-block btn-primary" type="submit"><?php echo get_phrase('update_paytm_keys'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
