<?php $__env->startSection('main'); ?>

    <section id="form-login"><!--form-->
        <div class="container">
            <div class="row">
                <h2 class="title text-center"><?php echo e($title, false); ?></h2>
                <div class="col-sm-4 col-sm-offset-1">
                    <div class="login-form"><!--login form-->
                        <h2>Login to your account</h2>
                        <form action="<?php echo e(route('postLogin'), false); ?>" method="post"  class="box">
                            <?php echo csrf_field(); ?>

                            <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : '', false); ?>">
                                <label for="email" class="control-label">Your email</label>
                                    <input class="is_required validate account_input form-control <?php echo e(($errors->has('email'))?"input-error":"", false); ?>"   type="text" name="email" value="<?php echo e(old('email'), false); ?>" >
                                        <?php if($errors->has('email')): ?>
                                            <span class="help-block">
                                                <?php echo e($errors->first('email'), false); ?>

                                            </span>
                                        <?php endif; ?>

                            </div>
                          
                            <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : '', false); ?>">
                                <label for="password" class="control-label">Your password</label>
                                    <input class="is_required validate account_input form-control <?php echo e(($errors->has('password'))?"input-error":"", false); ?>"   type="password" " name="password" value="" >
                                        <?php if($errors->has('password')): ?>
                                            <span class="help-block">
                                                <?php echo e($errors->first('password'), false); ?>

                                            </span>
                                        <?php endif; ?>

                            </div>
                        <p class="lost_password form-group">
                            <a class="btn btn-link" href="<?php echo e(route('forgot'), false); ?>">
                                Forgot password?
                            </a>
                            <br>
                        </p>
                            <button type="submit" name="SubmitLogin" class="btn btn-default">Login</button>
                        </form>
                    </div><!--/login form-->
                </div>
                <div class="col-sm-1">
                    <h2 class="or">OR</h2>
                </div>
                <!--   sign up form    -->
                <div class="col-sm-4">
                    <div class="signup-form"><!--sign up form-->
                        <h2>New User Signup!</h2>
                        <form action="<?php echo e(route('postRegister'), false); ?>" method="post"  class="box">
                            <?php echo csrf_field(); ?>

                <div class="form_content <?php echo e((old('check_red'))?'in':'', false); ?>" id="collapseExample">
                    <div class="form-group<?php echo e($errors->has('reg_name') ? ' has-error' : '', false); ?>">
                        <input  type="text" class="is_required validate account_input form-control <?php echo e(($errors->has('reg_name'))?"input-error":"", false); ?>"   name="reg_name" placeholder="Name" value="<?php echo e(old('reg_name'), false); ?>">
                        <?php if($errors->has('reg_name')): ?>
                        <span class="help-block">
                            <?php echo e($errors->first('reg_name'), false); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group<?php echo e($errors->has('reg_email') ? ' has-error' : '', false); ?>">
                        <input  type="text" class="is_required validate account_input form-control <?php echo e(($errors->has('reg_email'))?"input-error":"", false); ?>"   name="reg_email" placeholder="Email" value="<?php echo e(old('reg_email'), false); ?>">
                        <?php if($errors->has('reg_email')): ?>
                        <span class="help-block">
                            <?php echo e($errors->first('reg_email'), false); ?>

                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group<?php echo e($errors->has('reg_phone') ? ' has-error' : '', false); ?>">
                        <input  type="text" class="is_required validate account_input form-control <?php echo e(($errors->has('reg_phone'))?"input-error":"", false); ?>"   name="reg_phone" placeholder="Phone" value="<?php echo e(old('reg_phone'), false); ?>">
                        <?php if($errors->has('reg_phone')): ?>
                        <span class="help-block">
                            <?php echo e($errors->first('reg_phone'), false); ?>

                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group<?php echo e($errors->has('reg_address1') ? ' has-error' : '', false); ?>">
                        <input  type="text" class="is_required validate account_input form-control <?php echo e(($errors->has('reg_address1'))?"input-error":"", false); ?>"   name="reg_address1" placeholder="Address 1" value="<?php echo e(old('reg_address1'), false); ?>">
                        <?php if($errors->has('reg_address1')): ?>
                        <span class="help-block">
                            <?php echo e($errors->first('reg_address1'), false); ?>

                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group<?php echo e($errors->has('reg_address2') ? ' has-error' : '', false); ?>">
                        <input  type="text" class="is_required validate account_input form-control <?php echo e(($errors->has('reg_address2'))?"input-error":"", false); ?>"   name="reg_address2" placeholder="Address 2" value="<?php echo e(old('reg_address2'), false); ?>">
                        <?php if($errors->has('reg_address2')): ?>
                        <span class="help-block">
                            <?php echo e($errors->first('reg_address2'), false); ?>

                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group<?php echo e($errors->has('reg_password') ? ' has-error' : '', false); ?>">
                        <input  type="password" class="is_required validate account_input form-control <?php echo e(($errors->has('reg_password'))?"input-error":"", false); ?>"   name="reg_password" placeholder="Password" value="">
                        <?php if($errors->has('reg_password')): ?>
                        <span class="help-block">
                            <?php echo e($errors->first('reg_password'), false); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group<?php echo e($errors->has('reg_password_confirmation') ? ' has-error' : '', false); ?>">
                        <input type="password" class="is_required validate account_input form-control <?php echo e(($errors->has('reg_password_confirmation'))?"input-error":"", false); ?>"  placeholder="Password confirm" name="reg_password_confirmation" value="">
                        <?php if($errors->has('reg_password_confirmation')): ?>
                        <span class="help-block">
                            <?php echo e($errors->first('reg_password_confirmation'), false); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="check_red" value="1">
                    <div class="submit">
                        <button type="submit" name="SubmitCreate" class="btn btn-default">Signup</button>
                    </div>
                </div>

                        </form>
                    </div><!--/sign up form-->
                </div>
            </div>
        </div>
    </section><!--/form-->
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