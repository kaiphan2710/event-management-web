

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-tachometer-alt me-2"></i>Organiser Dashboard
            </h1>
            <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create New Event
            </a>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Welcome back, <?php echo e($organiser->name); ?>! Here's an overview of your events and their current status.
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                        <h5 class="card-title"><?php echo e(count($eventsReport)); ?></h5>
                        <p class="card-text text-muted">Total Events</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-ticket-alt fa-2x text-success mb-2"></i>
                        <h5 class="card-title" id="total-bookings"><?php echo e(array_sum(array_column($eventsReport, 'current_bookings'))); ?></h5>
                        <p class="card-text text-muted">Total Bookings</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                        <h5 class="card-title" id="total-waitlist"><?php echo e(array_sum(array_column($eventsReport, 'waitlist_count'))); ?></h5>
                        <p class="card-text text-muted">Waitlist Entries</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x text-info mb-2"></i>
                        <h5 class="card-title" id="total-capacity"><?php echo e(array_sum(array_column($eventsReport, 'capacity'))); ?></h5>
                        <p class="card-text text-muted">Total Capacity</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Report -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Events Report
                    <small class="text-muted ms-2">(Generated using raw SQL query)</small>
                </h5>
            </div>
            <div class="card-body">
                <?php if(count($eventsReport) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th>Event Title</th>
                                    <th>Date & Time</th>
                                    <th>Location</th>
                                    <th>Capacity</th>
                                    <th>Bookings</th>
                                    <th>Remaining</th>
                                    <th>Waitlist</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $eventsReport; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($event->title); ?></strong>
                                        </td>
                                        <td>
                                            <div><?php echo e(\Carbon\Carbon::parse($event->date)->format('M d, Y')); ?></div>
                                            <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($event->time)->format('g:i A')); ?></small>
                                        </td>
                                        <td><?php echo e($event->location); ?></td>
                                        <td><?php echo e($event->capacity); ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo e($event->current_bookings); ?></span>
                                        </td>
                                        <td>
                                            <?php if($event->remaining_spots == 0): ?>
                                                <span class="badge bg-danger">Full</span>
                                            <?php elseif($event->remaining_spots <= $event->capacity * 0.2): ?>
                                                <span class="badge bg-warning"><?php echo e($event->remaining_spots); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><?php echo e($event->remaining_spots); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($event->waitlist_count > 0): ?>
                                                <span class="badge bg-info"><?php echo e($event->waitlist_count); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($event->remaining_spots == 0): ?>
                                                <span class="badge bg-danger">Full</span>
                                            <?php elseif($event->date < now()->toDateString()): ?>
                                                <span class="badge bg-secondary">Past</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo e(route('events.show', $event->id)); ?>" 
                                                   class="btn btn-outline-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('events.edit', $event->id)); ?>" 
                                                   class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if($event->waitlist_count > 0): ?>
                                                    <a href="<?php echo e(route('dashboard.waitlist', $event->id)); ?>" 
                                                       class="btn btn-outline-info" title="Waitlist">
                                                        <i class="fas fa-list"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-plus fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Events Created Yet</h4>
                        <p class="text-muted">Start by creating your first event to manage bookings and waitlists.</p>
                        <a href="<?php echo e(route('events.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create Your First Event
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/kaiphan/Assignment/resources/views/dashboard/index.blade.php ENDPATH**/ ?>