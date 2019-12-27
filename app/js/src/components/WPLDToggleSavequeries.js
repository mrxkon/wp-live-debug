/**
 * WordPress dependencies.
 */
import { FormToggle } from '@wordpress/components';
import { withState } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';

/**
 * Main.
 */
const WPLDToggleSavequeries = ( { checked, setState } ) => {
	return (
		<Fragment>
			<label
				htmlFor="enable-savequeries"
				className="components-toggle-control__label"
			>SAVEQUERIES</label>
			<FormToggle
				id="enable-savequeries"
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
} )( WPLDToggleSavequeries );
