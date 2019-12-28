/**
 * WordPress dependencies.
 */
import { __ } from '@wordpress/i18n';
import { Component, Fragment } from '@wordpress/element';
import { SelectControl } from '@wordpress/components';

/**
 * Main.
 */
class LogList extends Component {
	render() {
		const size = '25%';
		return (
			<Fragment>
				<label htmlFor="log-list">
					{ __( 'Viewing:', 'wp-live-debug' ) }
				</label>
				<SelectControl
					id="log-list"
					value={ size }
					options={ [
						{ label: 'Big', value: '100%' },
						{ label: 'Medium', value: '50%' },
						{ label: 'Small', value: '25%' },
					] }
					//onChange={ ( size ) => { this.setState( { size } ) } }
				/>
			</Fragment>
		);
	}
}

export default LogList;
