/**
 * Internal Dependencies.
 */
import WPLDHeader from './components/WPLDHeader';
import WPLDContent from './components/WPLDContent';
import WPLDSidebar from './components/WPLDSidebar';

/**
 * Main.
 */
const App = () => {
	return (
		<>
			<WPLDHeader />
			<WPLDContent />
			<WPLDSidebar />
		</>
	);
};

export default App;
