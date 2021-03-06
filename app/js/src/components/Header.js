/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';

/**
 * Main.
 *
 * @param {Object} props
 */
const Header = ( props ) => {
	return (
		<>
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
					{ props.hasManualBackup ? (
						<Button
							id="wp-live-debug-restore"
							isPrimary
							onClick={ props.BackupActions }
						>
							{ __( 'Restore wp-config', 'wp-live-debug' ) }
						</Button>
					) : (
						<Button
							id="wp-live-debug-backup"
							isPrimary
							onClick={ props.BackupActions }
						>
							{ __( 'Backup wp-config', 'wp-live-debug' ) }
						</Button>
					) }
				</div>
			</div>
		</>
	);
};

export default Header;
