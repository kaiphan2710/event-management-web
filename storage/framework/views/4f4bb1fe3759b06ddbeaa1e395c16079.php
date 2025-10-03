

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-calendar-alt me-2"></i>Upcoming Events
            </h1>
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isOrganiser()): ?>
                    <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Event
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if($events->count() > 0): ?>
            <div class="row">
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo e($event->title); ?></h5>
                                <p class="card-text text-muted"><?php echo e(Str::limit($event->description, 100)); ?></p>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-calendar me-2"></i>
                                        <small class="text-muted"><?php echo e($event->date->format('M d, Y')); ?></small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-clock me-2"></i>
                                        <small class="text-muted"><?php echo e($event->time); ?></small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <small class="text-muted"><?php echo e($event->location); ?></small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users me-2"></i>
                                        <small class="text-muted">
                                            <?php echo e($event->getCurrentBookings()); ?>/<?php echo e($event->capacity); ?> booked
                                        </small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar 
                                            <?php if($event->getRemainingSpots() == 0): ?> bg-danger
                                            <?php elseif($event->getRemainingSpots() <= $event->capacity * 0.2): ?> bg-warning
                                            <?php else: ?> bg-success <?php endif; ?>" 
                                            role="progressbar" 
                                            style="width: <?php echo e(($event->getCurrentBookings() / $event->capacity) * 100); ?>%">
                                        </div>
                                    </div>
                                    <?php if($event->getRemainingSpots() == 0): ?>
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Event Full
                                        </small>
                                    <?php else: ?>
                                        <small class="text-muted"><?php echo e($event->getRemainingSpots()); ?> spots remaining</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-user-tie me-1"></i><?php echo e($event->organiser->name); ?>

                                    </small>
                                    <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    Showing <?php echo e($events->firstItem()); ?> to <?php echo e($events->lastItem()); ?> of <?php echo e($events->total()); ?> results
                </div>
                <nav>
                    <?php echo e($events->onEachSide(1)->links('pagination::bootstrap-5')); ?>

                </nav>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Upcoming Events</h4>
                <p class="text-muted">There are currently no upcoming events available.</p>
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isOrganiser()): ?>
                        <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create the First Event
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/kaiphan/Assignment/resources/views/events/index.blade.php ENDPATH**/ ?>