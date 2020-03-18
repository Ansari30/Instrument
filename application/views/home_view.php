<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
//header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

if($currentdeal_active){ ?>
  
      
        <ul>
        <?php if($currentdeal_active){ 
                $dealproducts = explode(',',$currentdeal_active[0]['products']);

                $this->general->set_table('products');
                for($k=0;$k < count($dealproducts);$k++){
                     
                    $productinfo =  $this->general->get('*',array('product_id'=>$dealproducts[$k]));
                ?>
        <li>
            <div class="feature-div">
                <figure><a href="<?php echo $productinfo[0]['product_id']; ?>.html">
                    <img src="<?php echo $productinfo[0]['image']; ?>" alt="" title=""></a>
                </figure>
                <h2><a href="<?php echo $productinfo[0]['product_id']; ?>.html"><?php echo $productinfo[0]['name']; ?></a></h2>
                <div class="reg-price">Regular price: $<?php echo $productinfo[0]['sale-price']; ?></div>
                <div class="sale-price">Sale price: $<?php echo $productinfo[0]['price']; ?></div> 
            </div>
            <?php if($productinfo[0]['option']=='Yes'){ ?>
            <div class="cart-sec">
                <form method="post" action="https://order.store.yahoo.net/yhst-43579267941482/cgi-bin/wg-order?yhst-43579267941482+<?php echo $productinfo[0]['product_id']; ?>"><input name="vwitem" value="<?php echo $productinfo[0]['product_id']; ?>" type="hidden"><input name="vwcatalog" value="yhst-43579267941482" type="hidden"><input value="" class="cart-btn" type="submit"><input name=".autodone" value="http://yhst-43579267941482.edit.store.luminatestores.net/RT/NEWEDIT.yhst-43579267941482/d27f1e418168/C00VDAA1" type="hidden">
                
                </form>
                </div>
            <?php } ?>

        </li>
        <?php } }  ?>
            
             
   
        
      </ul> 
 <?php } else {
    //echo "Go to admin";
    }?>