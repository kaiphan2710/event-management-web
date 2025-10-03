<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Waitlist - <?php echo e($event->title); ?></h5>
                <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-sm btn-outline-primary">Back to Event</a>
            </div>
            <div class="card-body">
                <?php if($waitlistEntries->count() === 0): ?>
                    <div class="text-center text-muted">No one is on the waitlist yet.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $waitlistEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($entry->position); ?></td>
                                        <td><?php echo e($entry->user->name); ?></td>
                                        <td><?php echo e($entry->user->email); ?></td>
                                        <td><?php echo e($entry->joined_at->format('Y-m-d H:i')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/kaiphan/Assignment/resources/views/dashboard/waitlist.blade.php ENDPATH**/ ?>