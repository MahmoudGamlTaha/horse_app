<?php $__env->startSection('main'); ?>

     <div id="contact-page" class="container">
        <div class="bg">
            <div class="row">
                <div class="col-sm-12">
                    <h2 class="title text-center"><?php echo e($title, false); ?></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="contact-form">
                        <h2 class="title text-center"><?php echo e(trans('language.contact_form.title'), false); ?></h2>
                        <form method="post" action="<?php echo e(route('postContact'), false); ?>" class="contact-form">
                        <?php echo e(csrf_field(), false); ?>

                        <div id="contactFormWrapper" style="margin: 30px;">
                        <div class="row">
                                <div class="col-md-12 collapsed-block">
                                    <?php echo $page->content; ?>

                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-sm-4 form-group <?php echo e($errors->has('name') ? ' has-error' : '', false); ?>">
                                            <label><?php echo e(trans('language.contact_form.name'), false); ?>:</label>
                                            <input type="text"  class="form-control <?php echo e(($errors->has('name'))?"input-error":"", false); ?>"  name="name" placeholder="Your name..." value="<?php echo e(old('name'), false); ?>">
                                            <?php if($errors->has('name')): ?>
                                                <span class="help-block">
                                                    <?php echo e($errors->first('name'), false); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-sm-4 form-group <?php echo e($errors->has('email') ? ' has-error' : '', false); ?>">
                                            <label><?php echo e(trans('language.contact_form.email'), false); ?>:</label>
                                            <input  type="email" class="form-control <?php echo e(($errors->has('email'))?"input-error":"", false); ?>"  name="email" placeholder="Your email..." value="<?php echo e(old('email'), false); ?>">
                                            <?php if($errors->has('email')): ?>
                                                <span class="help-block">
                                                    <?php echo e($errors->first('email'), false); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-sm-4 form-group <?php echo e($errors->has('phone') ? ' has-error' : '', false); ?>">
                                            <label><?php echo e(trans('language.contact_form.phone'), false); ?>:</label>
                                            <input  type="telephone" class="form-control <?php echo e(($errors->has('phone'))?"input-error":"", false); ?>"  name="phone" placeholder="Your phone..." value="<?php echo e(old('phone'), false); ?>">
                                            <?php if($errors->has('phone')): ?>
                                                <span class="help-block">
                                                    <?php echo e($errors->first('phone'), false); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 form-group <?php echo e($errors->has('title') ? ' has-error' : '', false); ?>">
                                            <label class="control-label"><?php echo e(trans('language.contact_form.subject'), false); ?>:</label>
                                            <input  type="text" class="form-control <?php echo e(($errors->has('title'))?"input-error":"", false); ?>"  name="title" placeholder="Subject..." value="<?php echo e(old('title'), false); ?>">
                                            <?php if($errors->has('title')): ?>
                                                <span class="help-block">
                                                    <?php echo e($errors->first('title'), false); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-sm-12 form-group <?php echo e($errors->has('content') ? ' has-error' : '', false); ?>">
                                            <label class="control-label"><?php echo e(trans('language.contact_form.content'), false); ?>:</label>
                                            <textarea  class="form-control <?php echo e(($errors->has('content'))?"input-error":"", false); ?>" rows="5" cols="75"  name="content" placeholder="Your Message..."><?php echo e(old('content'), false); ?></textarea>
                                            <?php if($errors->has('content')): ?>
                                                <span class="help-block">
                                                    <?php echo e($errors->first('content'), false); ?>

                                                </span>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                    <div class="btn-toolbar form-group">
                                        <input type="submit"  value="<?php echo e(trans('language.contact_form.submit'), false); ?>" class="btn btn-primary">
                                    </div>
                                </div>
                        </div>
                        </div><!-- contactFormWrapper -->
                        </form>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="contact-info">
                        <h2 class="title text-center"><?php echo e(trans('language.contact_form.info'), false); ?></h2>
                        <address>
                            <p><?php echo e($configsGlobal['title'], false); ?></p>
                            <p><?php echo e($configsGlobal['address'], false); ?></p>
                            <p><?php echo e($configsGlobal['long_phone'], false); ?></p>
                            <p><?php echo e($configsGlobal['email'], false); ?></p>
                        </address>
                        <div class="social-networks">
                            <h2 class="title text-center">Social Networking</h2>
                            <ul>
                                <li>
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-google-plus"></i></a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-youtube"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/#contact-page-->



  <!-- Main Container -->
  <div class="main-container col1-layout">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
  <div class="page_content">


  </div>
        </div>
    </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <div class="breadcrumbs">
        <ol class="breadcrumb">
          <li><a href="<?php echo e(route('home'), false); ?>">Home</a></li>
          <li class="active"><?php echo e($title, false); ?></li>
        </ol>
      </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'.shop_layout', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>