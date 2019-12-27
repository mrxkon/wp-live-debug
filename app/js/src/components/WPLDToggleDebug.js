/**
 * WordPress dependencies.
 */
import { FormToggle } from '@wordpress/components';
import { withState } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';

/**
 * Main.
 */
const WPLDToggleDebug = ( { checked, setState } ) => {
	return (
		<Fragment>
			<label
				htmlFor="enable-wp-debug"
				className="components-toggle-control__label"
			>WP_DEBUG</label>
			<FormToggle
				id="enable-wp-debug"
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
} )( WPLDToggleDebug );
