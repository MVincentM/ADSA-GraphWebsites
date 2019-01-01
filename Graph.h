#pragma once

#include "Resources.h"

class Graph
{
public:
	Graph();
	~Graph();

	void readFile(std::string path);
	std::vector< std::pair<int, int> > getPath(std::string start, std::string end);
	void writePath(std::vector< std::pair<int, int> > path);

private:
	std::vector<std::pair<int, int>> edges;
	std::map<std::string, int> nodes_url;
	std::vector<int> nodes;

	int getNodeNumber(std::string url)
	{
		return nodes_url[url];
	}
};

