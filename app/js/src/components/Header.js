/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Fragment, Component } from '@wordpress/element';
import { Button } from '@wordpress/components';

/**
 * Main.
 */
class Header extends Component {
	render() {
		const hasBackup = true;
		return (
			<Fragment>
				<div
					className="header"
					role="region"
					aria-label={ __( 'WP Live Debug Top Bar', 'wp-live-debug' ) }
					tabIndex="-1"
				>
					<div className="page-title">
						<h1 className="header-title">{ __( 'WP Live Debug', 'wp-live-debug' ) }</h1>
					</div>
					<div className="backup-restore">
						{ hasBackup ? (
							<Button
								id="wp-live-debug-restore"
								isPrimary
							>
								{ __( 'Restore wp-config', 'wp-live-debug' ) }
							</Button>
						) : (
							<Button
								id="wp-live-debug-backup"
								isPrimary
							>
								{ __( 'Backup wp-config', 'wp-live-debug' ) }
							</Button>
						) }
					</div>
				</div>
			</Fragment>
		);
	}
}

export default Header;
