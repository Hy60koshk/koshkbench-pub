/**===============================================================**
Constructor:
	no args - new empty list will be created
	instance of Array - new list with elements of the given array
	one non-Array or multiple args - args will be added as elements of the new list

Getters:
	Count - the number of elements in the list

Methods:
	addFirst(value) - добавляет элемент в начало списка, возвращает новую ноду
	addLast(value) - добавляет элемент в конец списка, возвращает новую ноду
	add(value) - алиас для addLast
	detachFirst() - убирает элемент из начала списка, возвращает вынутую ноду
	detachLast() - убирает элемент из конца списка, возвращает вынутую ноду
	shiftToHead() - переставляет последний элемент в начало списка. Возвращает переставленную ноду
	shiftToTail() - переставляет первый элемент в конец списка. Возвращает переставленную ноду
	forEach(action(node)) - выполняет action для каждой ноды
	clear() - аккуратно отцепляет все ноды, очищая список
	remove(value):
		если value - нода, то если она относится к текущему списку, то вынимает её
		если value - не нода, то вынимает все ноды, чьё содержимое строго (===) равно value
	contains(value):
		если value - нода, то проверяется, относится ли она к этому списку (проверяется owner)
		если value - не нода, то восвращает true, если в списке существует нода, чьё содержимое === value
	find(value) - возвращает ближайшую к началу ноду, чьё содержимое === value
	find(predicate(value)) - возвращает ближайшую к началу ноду, чьё содержимое удовлетворяет предикату
	findLast - как find, но возвращает ближайшую к концу ноду
**===============================================================**/
;
function LinkedListNode(value, owner) {
	this.value = value
	this.next = null
	this.prev = null
	this.owner = owner || null
}

function List() {
	this.head = null
	this.tail = null
	var _count = 0
	var _listself = this

	this.addFirst = function(value) {
		var newNode = (value instanceof LinkedListNode) ? value : new LinkedListNode(value)
		if (this.head == null) {
			this.head = newNode
			this.tail = newNode
		}
		else {
			this.head.prev = newNode
			newNode.next = this.head
			this.head = newNode
		}
		newNode.owner = _listself
		_count++
		return newNode
	}
	this.addLast = this.add = function(value) {
		var newNode = (value instanceof LinkedListNode) ? value : new LinkedListNode(value)
		if (this.head == null) {
			this.head = newNode
			this.tail = newNode
		}
		else {
			this.tail.next = newNode
			newNode.prev = this.tail
			this.tail = newNode
		}
		newNode.owner = _listself
		_count++
		return newNode
	}
	this.detachFirst = function() {
		if (this.head == null) {
			return null
		}
		var node = this.head
		if (this.head.next == null) {
			this.head = null
			this.tail = null
		} else {
			this.head.next.prev = null
			this.head = this.head.next
			node.next = null
		}
		node.owner = null
		_count--
		return node
	}
	this.detachLast = function() {
		if (this.head == null) {
			return null
		}
		var node = this.tail
		if (this.tail.prev == null) {
			this.head = null
			this.tail = null
		}
		else {
			this.tail.prev.next = null
			this.tail = this.tail.prev
			node.prev = null
		}
		node.owner = null
		_count--
		return node
	}
	this.shiftToHead = function() {
		if (this.head == null) {
			console.log('The list is empty, nothing to shift')
			return null
		}
		if (this.head.next == null) {
			return this.head
		}
		var node = this.head
		this.head.next.prev = null
		this.head = this.head.next
		node.next = null
		this.tail.next = node
		node.prev = this.tail
		this.tail = node
		return node
	}
	this.shiftToTail = function() {
		if (this.head == null) {
			console.log('The list is empty, nothing to shift')
			return null
		}
		if (this.head.next == null) {
			return this.head
		}
		var node = this.tail
		this.tail.prev.next = null
		this.tail = this.tail.prev
		node.prev = null
		this.head.prev = node
		node.next = this.head
		this.head = node
		return node
	}
	this.forEach = function(action) {
		var node = this.head
		if (node == null)
			return
		do {
			var nextNode = node.next
			action(node)
			node = nextNode
		} while (node);
	}
	this.clear = function() {
		while (this.tail) this.detachLast()
	}
	this.remove = function(value) {
		var list = this;
		if (value instanceof LinkedListNode) {
			if (value.owner != _listself) {
				return
			}
			var node = value
			if (node.prev) {
				node.prev.next = node.next
			}
			else {
				list.head = node.next
			}
			if (node.next) {
				node.next.prev = node.prev
			}
			else {
				list.tail = node.prev
			}
			node.next = null
			node.prev = null
			node.owner = null
			_count--
		}
		else {
			this.forEach(function(node) {
				if (node.value === value) {
					_listself.remove(node)
				}
			})
		}
	}
	this.contains = function(value) {
		if (value instanceof LinkedListNode) {
			return value.owner == _listself
		}
		var node = this.head
		if (node == null)
			return false
		do {
			if (node.value === value) {
				return true
			}
			node = node.next
		} while (node)
		return false
	}
	this.find = function(value) {
		if (!this.head)
			return undefined
		var node = this.head
		var predicate;
		if (typeof value == 'function') {
			predicate = value
		} else {
			predicate = function(v) {
				return v === value
			}
		}
		do {
			if (predicate(node.value)) {
				return node
			}
			node = node.next
		} while (node)
		return undefined
	}
	this.findLast = function(value) {
		if (!this.head)
			return undefined
		var node = this.tail
		var predicate;
		if (typeof value == 'function') {
			predicate = value
		} else {
			predicate = function(v) {
				return v === value
			}
		}
		do {
			if (predicate(node.value)) {
				return node
			}
			node = node.prev
		} while (node)
		return undefined
	}
	this.swapBackward = function(value) {
		if (_count < 2) {
			return false
		}
		let node = null
		if (value instanceof LinkedListNode) {
			if (this.contains(value)) {
				node = value
			}
			else {
				value = value.value
			}
		}
		if (!node) {
			if (this.head.value != value) {
				node = this.find(value)
			}
		}
		if (!node) {
			return false
		}
		let swapNode = node.prev
		if (swapNode == this.head) {
			if (node == this.tail) {
				this.tail = node.next = swapNode
				this.head = swapNode.prev = node
				node.prev = null
				swapNode.next = null
			} else {
				this.head = swapNode.prev = node
				swapNode.next = node.next
				swapNode.next.prev = swapNode
				node.next = swapNode
				node.prev = null
			}
		} else if (node == this.tail) {
			this.tail = node.next = swapNode
			swapNode.next = null
			node.prev = swapNode.prev
			node.prev.next = node
			swapNode.prev = node
		} else {
			swapNode.next = node.next
			swapNode.next.prev = swapNode
			node.prev = swapNode.prev
			node.prev.next = node
			swapNode.prev = node
			node.next = swapNode
		}
		return true
	}
	this.swapForward = function(value) {
		if (_count < 2) {
			return false
		}
		let node = null
		if (value instanceof LinkedListNode) {
			if (this.contains(value)) {
				node = value
			}
			else {
				value = value.value
			}
		}
		if (!node) {
			if (this.tail.value != value) {
				node = this.find(value)
			}
		}
		if (!node) {
			return false
		}
		let swapNode = node.next
		if (swapNode == this.tail) {
			if (node == this.head) {
				this.tail = swapNode.next = node
				this.head = node.prev = swapNode
				node.next = null
				swapNode.prev = null
			} else {
				this.tail = swapNode.next = node
				swapNode.prev = node.prev
				swapNode.prev.next = swapNode
				node.prev = swapNode
				node.next = null
			}
		} else if (node == this.head) {
			this.head = node.prev = swapNode
			node.next = swapNode.next
			node.next.prev = node
			swapNode.next = node
			swapNode.prev = null
		} else {
			swapNode.prev = node.prev
			swapNode.prev.next = swapNode
			node.next = swapNode.next
			node.next.prev = node
			swapNode.next = node
			node.prev = swapNode
		}
		return true
	}
	Object.defineProperty(this, "Count", {
		get: function () { return _count; }
	})
	
	if (arguments.length == 1 && arguments[0] instanceof Array) {
		var arg = arguments[0]
		var _list = this
		arg.forEach(function(value) {
			_list.addLast(value)
		})
	}
	else if (arguments.length > 0) {
		var _list = this
		for (var i = 0; i < arguments.length; i++) {
			_list.addLast(arguments[i])
		}
	}
};