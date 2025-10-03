

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>Create Account
                </h4>
            </div>
            
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input id="name" type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="name" value="<?php echo e(old('name')); ?>" required autocomplete="name" autofocus>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="password" required autocomplete="new-password">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input id="password_confirmation" type="password" class="form-control" 
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="user_type" class="form-label">Account Type</label>
                        <select id="user_type" class="form-select <?php $__errorArgs = ['user_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="user_type" required>
                            <option value="">Select Account Type</option>
                            <option value="attendee" <?php echo e(old('user_type') === 'attendee' ? 'selected' : ''); ?>>
                                Attendee - I want to book events
                            </option>
                            <option value="organiser" <?php echo e(old('user_type') === 'organiser' ? 'selected' : ''); ?>>
                                Organiser - I want to create and manage events
                            </option>
                        </select>
                        <?php $__errorArgs = ['user_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number (Optional)</label>
                            <input id="phone" type="tel" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   name="phone" value="<?php echo e(old('phone')); ?>">
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address (Optional)</label>
                            <textarea id="address" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      name="address" rows="2"><?php echo e(old('address')); ?></textarea>
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Privacy Policy and Terms Consent -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input <?php $__errorArgs = ['privacy_policy_agreed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   type="checkbox" name="privacy_policy_agreed" id="privacy_policy_agreed" 
                                   value="1" <?php echo e(old('privacy_policy_agreed') ? 'checked' : ''); ?> required>
                            <label class="form-check-label" for="privacy_policy_agreed">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>
                            </label>
                            <?php $__errorArgs = ['privacy_policy_agreed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input <?php $__errorArgs = ['terms_agreed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   type="checkbox" name="terms_agreed" id="terms_agreed" 
                                   value="1" <?php echo e(old('terms_agreed') ? 'checked' : ''); ?> required>
                            <label class="form-check-label" for="terms_agreed">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms of Use</a>
                            </label>
                            <?php $__errorArgs = ['terms_agreed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i>Create Account
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="card-footer text-center">
                <p class="mb-0">Already have an account? 
                    <a href="<?php echo e(route('login')); ?>">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Policy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Privacy Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Data Collection</h6>
                <p>We collect the following information:</p>
                <ul>
                    <li><strong>Personal Information:</strong> Name, email address, phone number, and address (optional)</li>
                    <li><strong>Account Information:</strong> Password (hashed and encrypted)</li>
                    <li><strong>Booking History:</strong> Events you have booked or are waitlisted for</li>
                    <li><strong>Usage Data:</strong> How you interact with our platform</li>
                </ul>

                <h6>Why We Collect This Data</h6>
                <ul>
                    <li><strong>Authentication:</strong> To verify your identity and secure your account</li>
                    <li><strong>Event Participation:</strong> To manage your event bookings and waitlist positions</li>
                    <li><strong>Communication:</strong> To send you important updates about events and your account</li>
                    <li><strong>Service Improvement:</strong> To enhance our platform and user experience</li>
                </ul>

                <h6>Data Protection</h6>
                <ul>
                    <li><strong>Password Security:</strong> All passwords are hashed using industry-standard encryption</li>
                    <li><strong>Access Control:</strong> Your data is only accessible to authorized personnel</li>
                    <li><strong>Secure Storage:</strong> Data is stored in secure, encrypted databases</li>
                    <li><strong>Regular Backups:</strong> Your data is regularly backed up to prevent loss</li>
                </ul>

                <h6>Your Rights</h6>
                <ul>
                    <li><strong>View Data:</strong> You can view all data we have about you</li>
                    <li><strong>Update Data:</strong> You can update your personal information at any time</li>
                    <li><strong>Delete Data:</strong> You can request deletion of your account and associated data</li>
                    <li><strong>Data Portability:</strong> You can request a copy of your data in a portable format</li>
                </ul>

                <p><small class="text-muted">Last updated: <?php echo e(date('F d, Y')); ?></small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Terms of Use Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms of Use</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Acceptance of Terms</h6>
                <p>By creating an account, you agree to be bound by these Terms of Use and our Privacy Policy.</p>

                <h6>User Responsibilities</h6>
                <ul>
                    <li>Provide accurate and truthful information</li>
                    <li>Maintain the security of your account credentials</li>
                    <li>Respect other users and event organisers</li>
                    <li>Follow all applicable laws and regulations</li>
                </ul>

                <h6>Event Booking</h6>
                <ul>
                    <li>Bookings are subject to event capacity limits</li>
                    <li>You may only book one spot per event</li>
                    <li>Cancellation policies vary by event</li>
                    <li>Organisers may cancel events with appropriate notice</li>
                </ul>

                <h6>Waitlist System</h6>
                <ul>
                    <li>Waitlist positions are determined by first-come-first-served basis</li>
                    <li>You will be notified if a spot becomes available</li>
                    <li>You have a limited time to claim available spots</li>
                    <li>You may leave the waitlist at any time</li>
                </ul>

                <h6>Prohibited Activities</h6>
                <ul>
                    <li>Creating multiple accounts to circumvent booking limits</li>
                    <li>Sharing account credentials with others</li>
                    <li>Using the platform for illegal activities</li>
                    <li>Harassing or abusing other users</li>
                </ul>

                <h6>Account Termination</h6>
                <p>We reserve the right to suspend or terminate accounts that violate these terms or engage in prohibited activities.</p>

                <p><small class="text-muted">Last updated: <?php echo e(date('F d, Y')); ?></small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/kaiphan/Assignment/resources/views/auth/register.blade.php ENDPATH**/ ?>