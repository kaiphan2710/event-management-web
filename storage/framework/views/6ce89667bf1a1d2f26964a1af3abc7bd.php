

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-ticket-alt me-2"></i>My Bookings
            </h1>
            <a href="<?php echo e(route('events.index')); ?>" class="btn btn-outline-primary">
                <i class="fas fa-search me-1"></i>Browse Events
            </a>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Here are all the events you have successfully booked. You can view details or cancel bookings from here.
        </div>

        <?php if($bookings->count() > 0): ?>
            <div class="row">
                <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo e($booking->event->title); ?></h5>
                                <p class="card-text text-muted"><?php echo e(Str::limit($booking->event->description, 100)); ?></p>
                                
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-calendar me-2"></i>
                                        <small class="text-muted"><?php echo e($booking->event->date->format('M d, Y')); ?></small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-clock me-2"></i>
                                        <small class="text-muted"><?php echo e($booking->event->time); ?></small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <small class="text-muted"><?php echo e($booking->event->location); ?></small>
                                    </div>
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-user-tie me-2"></i>
                                        <small class="text-muted">Organiser: <?php echo e($booking->event->organiser->name); ?></small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        <small class="text-muted">Booked: <?php echo e($booking->booking_date->format('M d, Y g:i A')); ?></small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <?php if($booking->event->date < now()->toDateString()): ?>
                                        <span class="badge bg-secondary">Past Event</span>
                                    <?php elseif($booking->event->date == now()->toDateString()): ?>
                                        <span class="badge bg-warning">Today</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Upcoming</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent border-top">
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo e(route('events.show', $booking->event)); ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View Event
                                    </a>
                                    <?php if($booking->event->date >= now()->toDateString()): ?>
                                        <form method="POST" action="<?php echo e(route('bookings.destroy', $booking)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                <i class="fas fa-times me-1"></i>Cancel
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                <?php echo e($bookings->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Bookings Yet</h4>
                <p class="text-muted">You haven't booked any events yet. Start exploring available events!</p>
                <a href="<?php echo e(route('events.index')); ?>" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i>Browse Events
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/kaiphan/Assignment/resources/views/bookings/index.blade.php ENDPATH**/ ?>