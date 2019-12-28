/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';

/**
 * Internal Dependencies.
 */
import Header from './components/Header';
import WPLDContent from './components/WPLDContent';
import Sidebar from './components/Sidebar';

/**
 * Main.
 */
class App extends Component {
	render() {
		return (
			<>
				<Header />
				<WPLDContent />
				<Sidebar />
			</>
		);
	}
}

export default App;
