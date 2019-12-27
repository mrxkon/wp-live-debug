/**
 * WordPress dependencies.
 */
import { FormToggle } from '@wordpress/components';
import { withState } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';

/**
 * Main.
 */
const WPLDToggleRefresh = ( { checked, setState } ) => {
	return (
		<Fragment>
			<label
				htmlFor="enable-auto-refresh"
				className="components-toggle-control__label"
			>Auto Refresh</label>
			<FormToggle
				id="enable-auto-refresh"
				checked={ checked }
				onChange={ () => setState( ( state ) => ( { checked: ! state.checked } ) ) }
			/>
		</Fragment>
	);
};

export default withState( ( checked, setState ) => {
	return (
		checked,
		setState
	);
} )( WPLDToggleRefresh );
