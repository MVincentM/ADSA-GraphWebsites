#include "Resources.h"
#include "Graph.h"

using namespace std;

int main()
{
	Graph graph;
	graph.readFile("C:\\Users\\Arthur\\source\\repos\\ADSA\\ADSA\\crawler.txt");

	vector< pair<int, int> > path = graph.getPath("https://www.clubic.com/", "https://www.clubic.com/test-achat/televiseur/shopping-1070060-3-tx-49fx780e.html");
	graph.writePath(path);
	system("pause");
	return 0;
}