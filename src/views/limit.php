<?php defined('SHIELDON_VIEW') || exit('Life is short, why are you wasting time?');
/*
 * This file is part of the Shieldon package.
 *
 * (c) Terry L. <contact@terryl.in>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use function Shieldon\Helper\_e;

$imgsrc = <<< EOF
data:image/png;base64,
iVBORw0KGgoAAAANSUhEUgAAAGAAAABgCAYAAADimHc4AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFn
ZVJlYWR5ccllPAAAB8dpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/
IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6
bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQyIDc5LjE2MDkyNCwgMjAxNy8w
Ny8xMy0wMTowNjozOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9y
Zy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIg
eG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1
cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hh
cC8xLjAvbW0vIiB4bWxuczpzdEV2dD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jl
c291cmNlRXZlbnQjIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBl
L1Jlc291cmNlUmVmIyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hv
cC8xLjAvIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHhtcDpD
cmVhdGVEYXRlPSIyMDE5LTEwLTEwVDE0OjMzOjMwKzA4OjAwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDE5
LTEwLTEwVDE5OjIyOjI1KzA4OjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAxOS0xMC0xMFQxOToyMjoyNSsw
ODowMCIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6M0M3MDJG
OEZFQjUwMTFFOTg0RkFDMURGQjQ4NTIwNDciIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6M0M3MDJG
OTBFQjUwMTFFOTg0RkFDMURGQjQ4NTIwNDciIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRp
ZDo1ZDcwMjg2My1iZDUyLTlmNDgtYjEyYy1mYWJkMzM5MDY0NGMiPiA8eG1wTU06SGlzdG9yeT4gPHJk
ZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAu
aWlkOjVkNzAyODYzLWJkNTItOWY0OC1iMTJjLWZhYmQzMzkwNjQ0YyIgc3RFdnQ6d2hlbj0iMjAxOS0x
MC0xMFQxNDozMzozMCswODowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIEND
IChXaW5kb3dzKSIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9
InhtcC5paWQ6Y2QzMGQ2ZDktNmQ5MS04MzRiLTgyNmEtNzM1NTc3NWMzZTFiIiBzdEV2dDp3aGVuPSIy
MDE5LTEwLTEwVDE1OjAxOjIxKzA4OjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3No
b3AgQ0MgKFdpbmRvd3MpIiBzdEV2dDpjaGFuZ2VkPSIvIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJz
YXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo2Mjg4YjlkNi0zMjExLWQ5NDYtYjk5My1hOGYw
NTczNjRmZGIiIHN0RXZ0OndoZW49IjIwMTktMTAtMTBUMTc6MTU6MjQrMDg6MDAiIHN0RXZ0OnNvZnR3
YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDQyAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4g
PC9yZGY6U2VxPiA8L3htcE1NOkhpc3Rvcnk+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5j
ZUlEPSJ4bXAuaWlkOkU5RDI2OUU4RUIzRjExRTk5QzVGOTNEODhDMzBBRjZEIiBzdFJlZjpkb2N1bWVu
dElEPSJ4bXAuZGlkOkU5RDI2OUU5RUIzRjExRTk5QzVGOTNEODhDMzBBRjZEIi8+IDxwaG90b3Nob3A6
RG9jdW1lbnRBbmNlc3RvcnM+IDxyZGY6QmFnPiA8cmRmOmxpPmFkb2JlOmRvY2lkOnBob3Rvc2hvcDox
M2FiZTQwOC1hNzkxLTRkNGMtYTM1NC1lYjNiZGY3ZGYyY2Y8L3JkZjpsaT4gPC9yZGY6QmFnPiA8L3Bo
b3Rvc2hvcDpEb2N1bWVudEFuY2VzdG9ycz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94
OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6cscUqAAAYLUlEQVR42uxdCXxU5bX/33tn7qyZJftK
QtgXAeWBqKhYFa3wap9rXapd3Npaq6+2qPSn1mqLrX36s631V/ss7z1aFSm4ID5EtKiVYvEJKIvsSSSB
LJNl9v2d890bCJCQmSSTDMuBk0kmN3PvPf/vrN/5vislk0mcoqEjA3+57oPmbL0+K3ER8WidxxEXEBcT
O4hN+nERYh9xI3ET8efEO4k36++1Z+PNvTAzXwMgi2gY8enE04gvJB6hC7w/1EZcR7ya+O/EW3TOHg0Y
YhpOfBXx+cRfJpYH+PNdOp9GfLf+3hrid4lfJ/6/kxEAPu+VxN8knk0sDfL5z9f5YeL3iRcTL9K1ZVBJ
HuTz5RP/O/Eu4heJLxkC4R9J5xL/hriG+HHd15xwAKjE84m3E/9at/XZRuzUf6w78KeJy08UAL5PvIP4
UWL3cRId8jVv002U6XgFYAbxR/poGobjj2zED+nh7FePNwB+SrxWDyePd2JTtIx4oQ5KVgNQoQv+wRMw
ab1Zzx/OyVYAvqxf4IwTuHLApvQD4h9kGwD3EK8gtuPkoKeIf58tACwg/g+cfHQH8ZKhBuBZ4nk4eYmz
+ZVDBcAfiG/HKeJSyruDDQCn7Leekv1BmkX82mAB8AM9ZT9Fh9O/Ej+faQAu1COAU9Q9cXX3R5kCgGem
/veUjHulXxLPzAQALHzDKfmmRG8gxbJFqgD8jHjKKbmmTFzaXprKgamM6InEP+ntIO6ukA0GyEaVfzjx
RCpJSEQjSMZi4vsUw9Prif/SXwCWpHZ9EpLxOOKxwAk5pMUAMxqhmMyIExBIJpDCZN5CaJXUYF8B4Fh/
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
TkSuQmCC
EOF;

?>
<!DOCTYPE html>
<html lang="<?php echo $langCode ?>">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">
    <title><?php _e('core', 'limit.title', 'Please line up'); ?></title>
    <?php echo '<style>' . $css . '</style>'; ?>
</head>
<body>
    <div id="wrapper" class="wrapper">
		<div class="inner">
			<div class="card">
				<div class="card-header"><?php _e('core', 'limit.heading', 'Please line up') ?></div>
				<div class="card-body">
					<div class="status-container">
						<div class="status-icon">
							<img src="<?php echo $imgsrc; ?>">
						</div>
						<div class="status-message">
                            <?php _e('core', 'limit.message', 'This page is limiting the number of people online. Please wait a moment.'); ?>
						</div>
					</div>
 
                    <div class="status-info">
                        <?php _e('core', 'lineup_info', '', ['<strong>' . $this->currentWaitNumber . '</strong>']); ?><br />

                        <?php if ($showOnlineInformation) : ?>
                            <?php _e('core', 'online_info', '', ['<strong>' . $this->sessionCount . '</strong>']); ?><br />
                        <?php endif; ?>

                        <br /><small><?php _e('core', 'keepalive_info', '', [$this->isLimitSession[1]]); ?></small>

                    </div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>