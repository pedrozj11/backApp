
/* Node_tree Table */ 

CREATE TABLE docebo.node_tree(
    idNode INT NOT NULL,
    level INT NOT NULL,
    iLeft INT NOT NULL,
    iRight INT NOT NULL,
    PRIMARY KEY(idNode)
);



/* Node_tree_names Table */ 

CREATE TABLE docebo.node_tree_names(
    idNode INT NOT NULL,
    language VARCHAR(255),
    nodeName VARCHAR(255),
    FOREIGN KEY(idNode) REFERENCES docebo.node_tree(idNode)
);


