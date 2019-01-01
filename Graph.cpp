#include "Graph.h"

using namespace std;

Graph::Graph()
{
	/*nodes = { 0, 1, 2, 3, 4, 5, 6 };
	edges.push_back(pair<int, int>(1, 2));
	edges.push_back(pair<int, int>(2, 5));
	edges.push_back(pair<int, int>(5, 6));
	edges.push_back(pair<int, int>(6, 4));
	edges.push_back(pair<int, int>(4, 2));
	edges.push_back(pair<int, int>(2, 3));
	edges.push_back(pair<int, int>(3, 4));
	edges.push_back(pair<int, int>(3, 2));*/
}

Graph::~Graph()
{
}

void Graph::readFile(std::string path)
{
	int index_node = nodes.size();

	ifstream in(path);
	if (!in) {
		cout << "Cannot open input file.\n";
		return;
	}
	
	std::string str;
	int i(0);
	while (std::getline(in, str)) 
	{
		i++;
		if (i % 1000 == 0)
			cout << i << endl;
		if (str.find(";") == std::string::npos)
			continue;
		std::string firstUrl = str.substr(0, str.find(";"));
		std::string secondUrl = str.substr(str.find(";") + 1);

		if (nodes_url.find(firstUrl) == nodes_url.end())
		{
			nodes.push_back(index_node++);
			nodes_url.insert(make_pair(firstUrl, index_node - 1));
		}
		if (nodes_url.find(secondUrl) == nodes_url.end())
		{
			nodes.push_back(index_node++);
			nodes_url.insert(make_pair(secondUrl, index_node - 1));
		}

		edges.push_back(pair<int, int>(getNodeNumber(firstUrl), getNodeNumber(secondUrl)));
	}

	in.close();
}

std::vector<std::pair<int, int>> Graph::getPath(std::string startURL, std::string endURL)
{
	int start(nodes_url[startURL]), end(nodes_url[endURL]);

	map<int, vector<int> > voisin;
	map<int, int> pred;
	map<int, bool> visite;
	for (unsigned int i = 0; i< nodes.size(); i++)
	{
		voisin.insert(make_pair(nodes[i], vector<int>()));
		pred.insert(make_pair(nodes[i], -1));
		visite.insert(make_pair(nodes[i], false));
	}


	for (unsigned int i = 0; i< edges.size(); i++)
	{
		pair<int, int> l = edges[i];
		voisin[l.first].push_back(l.second);
	}


	vector<int> file;

	file.push_back(start);
	visite[start] = true;
	bool continu = true;

	while (continu && file.size() != 0)
	{
		int s = file[0];
		file.erase(file.begin());
		for (int v : voisin[s])
		{

			if (visite[v] == false)
			{
				pred[v] = s;
				file.push_back(v);
				visite[v] = true;
				if (v == end)
				{
					continu = false;
					break;
				}
			}
		}

	}
	vector< pair<int, int> > res(0);
	if (continu == true)
	{
		cout << "no solution" << endl;
		return res;
	}
	else
	{

		int s = end;
		while (s != start)
		{

			res.push_back(make_pair(pred[s], s));
			s = pred[s];
		}


		return res;
	}
}

void Graph::writePath(std::vector<std::pair<int, int>> path)
{
	for (int i(path.size() - 1); i >= 0; --i)
	{
		cout << path[i].first << "-->" << path[i].second << endl;
	}
}
