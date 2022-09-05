"""Module includes a Controller to handle all DB operations"""
from typing import List, Dict, Any
import pymongo


class DatabaseController:
    def __init__(self, db_name, ip: str, port: int = 27017) -> None:
        self._db_client = pymongo.MongoClient(f"mongodb://{ip}:{port}")
        self._db = self._db_client[db_name]
        print(f"connected to MongoDB {db_name}")

    def list_databases(self) -> List[str]:
        """
        Method to list all existing Databases.

        Returns
        -------
        List[str]
            A list of strings containing the names of all existing databases
        """
        return self._db_client.list_database_names()

    def check_db_existence(self, name: str) -> bool:
        """
        Method to check if a database exists.

        Parameters
        ----------
        name : str
            name of DB to check.

        Returns
        -------
        bool
            `True`if DB already exists.
            `False` if DB not exists.
        """
        if name in self.list_databases():
            return True
        return False

    def list_collections(self) -> List[str]:
        """
        Method to list all existing collections.

        Returns
        -------
        List[str]
            a list including all collection names.
        """
        return self._db.list_collection_names()

    def write_item(self, collection_name: str, item: Dict[str, Any]) -> int:
        """
        Method to write item to collection.

        Parameters
        ----------
        collection_name : str
            name of collection to write the item to.
        item : Dict[str, Any]
            item to write to collection.

        Returns
        -------
        int
            generated id for written item.
        """
        collection = self._db[collection_name]
        resp = collection.insert_one(item)
        return resp.inserted_id

    def write_bulk_item(
        self, collection_name: str, items: List[Dict[str, Any]]
    ) -> List[int]:
        """
        Method to write multiple items to collection.

        Parameters
        ----------
        collection_name : str
            name of collection to write the items to.
        items : List[Dict[str, Any]]
            list of items to write to collection.

        Returns
        -------
        List[int]
            a list of generated ids for written items.
        """
        collection = self._db[collection_name]
        resp = collection.insert_many(items)
        return resp.inserted_ids

    def find_first_item(self, collection_name: str) -> Dict[str, Any]:
        """
        Method to find the first written item in collection.

        Parameters
        ----------
        collection_name : str
            name of collection.

        Returns
        -------
        Dict[str, Any]
            found item.
        """
        collection = self._db[collection_name]
        return collection.find_one()

    def get_all_items(self, collection_name: str) -> List[str, Any]:
        """
        Method to query all items from collection.

        Parameters
        ----------
        collection_name : str
            name of collection.

        Returns
        -------
        List[str, Any]
            list of dound items in collection.
        """
        collection = self._db[collection_name]
        return collection.find()

    def query(
        self,
        collection_name: str,
        search_key: Dict[str, Any],
        projection_expression: List[str] = None,
    ) -> List[Dict[str, Any]]:
        """
        Method to query items in collection.

        Parameters
        ----------
        collection_name : str
            name of collection to query items in.
        search_key : Dict[str, Any]
            a dict including the filter to find the items for
            example: {'name':'test'}, to find all items, where the name is test.
        projection_expression : List[str], optional
            a list of keys, to include in item.
        """
        if projection_expression:
            projection_filter = {key: 1 for key in projection_expression}
        else:
            projection_filter = None
        collection = self._db[collection_name]
        resp = collection.find(search_key, projection=projection_filter)
        return resp


if __name__ == "__main__":
    my_db = DatabaseController(db_name="test", ip="localhost")
    print(my_db.list_databases())
    print(my_db.list_collections())
    print(
        my_db.write_item(
            collection_name="test", item={"name": "John", "address": "Highway 37"}
        )
    )
